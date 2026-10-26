<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->orderBy('priority_level', 'asc')->get();
        
        $stats = [
            'total_completed' => Order::where('status', 'telah_diantar')->count() + 1250,
            'active_customers' => 480,
            'satisfaction_rate' => 99.4,
            'experience_years' => Carbon::now()->year - 1997,
        ];

        return view('public.landing', compact('packages', 'stats'));
    }

    public function submitOrder(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'package_code' => 'required|exists:packages,code',
            'estimated_weight' => 'nullable|numeric|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $package = Package::where('code', $validated['package_code'])->firstOrFail();
        $weight = $validated['estimated_weight'] ?? 3.0;
        $orderCode = 'CLN-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $estimatedHours = $package->estimated_hours ?: 24;

        $order = Order::create([
            'order_code' => $orderCode,
            'user_id' => Auth::id(), // if logged in as customer
            'customer_name' => $validated['name'],
            'customer_phone' => $validated['phone'],
            'customer_address' => $validated['address'],
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
            'customer_notes' => $validated['notes'] ?? null,
            'payment_status' => 'unpaid',
        ]);

        ActivityLog::log(
            Auth::user(),
            'ORDER_CREATED',
            'Pesanan Baru Diterima',
            "Pesanan {$orderCode} dibuat untuk {$validated['name']} ({$package->name})",
            $order,
            ['package' => $package->code, 'weight' => $weight]
        );

        return redirect()->route('public.track', ['code' => $orderCode])
            ->with('success', "Pesanan Anda berhasil dibuat dengan Kode: {$orderCode}! Supir kami akan segera menjemput laundry Anda.");
    }

    public function track(Request $request, $code = null)
    {
        $searchCode = $code ?? $request->query('code');
        $order = null;

        if ($searchCode) {
            $order = Order::with(['driver', 'operator', 'package', 'activityLogs'])
                ->where('order_code', trim($searchCode))
                ->first();
        }

        return view('public.track', compact('order', 'searchCode'));
    }
}

