<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Auth::user();

        // Active orders (in progress or pending delivery)
        $activeOrders = Order::with(['package', 'driver', 'operator', 'activityLogs'])
            ->where(function($q) use ($customer) {
                $q->where('user_id', $customer->id)
                  ->orWhere('customer_phone', $customer->phone);
            })
            ->whereNotIn('status', ['telah_diantar'])
            ->latest()
            ->get();

        // Recent completed orders preview
        $recentCompleted = Order::with(['package'])
            ->where(function($q) use ($customer) {
                $q->where('user_id', $customer->id)
                  ->orWhere('customer_phone', $customer->phone);
            })
            ->where('status', 'telah_diantar')
            ->latest('delivered_at')
            ->take(3)
            ->get();

        $packages = Package::where('is_active', true)->orderBy('priority_level', 'asc')->get();

        return view('customer.dashboard', compact('activeOrders', 'recentCompleted', 'packages'));
    }

    public function createOrder()
    {
        $packages = Package::where('is_active', true)->orderBy('priority_level', 'asc')->get();
        $customer = Auth::user();

        return view('customer.create_order', compact('packages', 'customer'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'package_code' => 'required|exists:packages,code',
            'weight' => 'nullable|numeric|min:1',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $customer = Auth::user();
        $package = Package::where('code', $validated['package_code'])->firstOrFail();
        $weight = $validated['weight'] ?? 3.0;
        $orderCode = 'CLN-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $estimatedHours = $package->estimated_hours ?: 24;

        $order = Order::create([
            'order_code' => $orderCode,
            'user_id' => $customer->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'package_id' => $package->id,
            'package_code' => $package->code,
            'package_name' => $package->name,
            'price_per_kg' => $package->price_per_kg,
            'estimated_hours' => $estimatedHours,
            'priority_level' => $package->priority_level,
            'weight' => $weight,
            'total_price' => $weight * $package->price_per_kg,
            'status' => 'menunggu_penjemputan',
            'estimated_completion_at' => Carbon::now()->addHours($estimatedHours),
            'customer_notes' => $validated['customer_notes'] ?? null,
            'payment_status' => 'unpaid',
        ]);

        ActivityLog::log(
            $customer,
            'ORDER_CREATED',
            'Pesanan Laundry Dibuat',
            "Pelanggan {$customer->name} memesan {$package->name} (Kode: {$orderCode}) dengan estimasi {$weight} kg.",
            $order,
            ['package' => $package->code, 'priority_level' => $package->priority_level]
        );

        return redirect()->route('customer.dashboard')
            ->with('success', "Pesanan {$orderCode} ({$package->name}) berhasil dibuat! Supir kami akan segera menjemput laundry ke lokasi Anda.");
    }

    public function history(Request $request)
    {
        $customer = Auth::user();

        $query = Order::with(['package', 'driver', 'operator'])
            ->where(function($q) use ($customer) {
                $q->where('user_id', $customer->id)
                  ->orWhere('customer_phone', $customer->phone);
            })
            ->where('status', 'telah_diantar')
            ->latest('delivered_at');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('package_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        $stats = [
            'total_completed' => $orders->total(),
            'total_spent' => Order::where('user_id', $customer->id)->where('status', 'telah_diantar')->sum('total_price'),
        ];

        return view('customer.history', compact('orders', 'stats'));
    }
}

