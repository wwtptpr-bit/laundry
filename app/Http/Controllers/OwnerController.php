<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => Order::where('status', 'telah_diantar')->sum('total_price'),
            'total_orders' => Order::count(),
            'active_orders' => Order::whereNotIn('status', ['telah_diantar'])->count(),
            'discrepancy_count' => Order::where('item_discrepancy', '!=', 0)->count(),
            'pending_delay_count' => Order::where('status', 'pending')->count(),
            'ready_deliver_count' => Order::where('status', 'siap_diantar')->count(),
        ];

        $packages = Package::orderBy('priority_level', 'asc')->get();
        $recentLogs = ActivityLog::with(['user', 'order'])->latest()->take(10)->get();
        $recentOrders = Order::with(['driver', 'operator', 'package'])->latest()->take(8)->get();

        return view('owner.dashboard', compact('stats', 'packages', 'recentLogs', 'recentOrders'));
    }

    public function prices()
    {
        $packages = Package::orderBy('priority_level', 'asc')->get();
        return view('owner.prices', compact('packages'));
    }

    public function updatePrice(Request $request, Package $package)
    {
        $validated = $request->validate([
            'price_per_kg' => 'required|numeric|min:100',
            'name' => 'required|string|max:100',
            'estimated_hours' => 'required|integer|min:1',
            'features_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'popular' => 'nullable|boolean',
            'badge_text' => 'nullable|string|max:50',
        ]);

        $oldPrice = $package->price_per_kg;

        $features = $package->features;
        if ($request->filled('features_text')) {
            $features = array_filter(array_map('trim', explode("\n", $request->input('features_text'))));
        }

        $package->update([
            'name' => $validated['name'],
            'price_per_kg' => $validated['price_per_kg'],
            'estimated_hours' => $validated['estimated_hours'],
            'features' => $features,
            'is_active' => $request->has('is_active'),
            'popular' => $request->has('popular'),
            'badge_text' => $validated['badge_text'] ?? null,
        ]);

        ActivityLog::log(
            Auth::user(),
            'PRICE_UPDATE',
            'Owner Mengubah Harga Layanan',
            "Harga {$package->name} diubah dari Rp " . number_format($oldPrice, 0, ',', '.') . " menjadi Rp " . number_format($package->price_per_kg, 0, ',', '.') . " (akan tampil di dashboard publik)",
            null,
            ['old_price' => $oldPrice, 'new_price' => $package->price_per_kg, 'package_code' => $package->code]
        );

        return redirect()->back()->with('success', "Harga {$package->name} berhasil diperbarui menjadi Rp " . number_format($package->price_per_kg, 0, ',', '.') . "/kg dan langsung tampil di halaman publik!");
    }

    public function monitoring(Request $request)
    {
        $query = ActivityLog::with(['user', 'order'])->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('order_code', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();
        $rolesCount = [
            'all' => ActivityLog::count(),
            'admin' => ActivityLog::where('role', 'admin')->count(),
            'supir' => ActivityLog::where('role', 'supir')->count(),
            'oprator' => ActivityLog::where('role', 'oprator')->count(),
            'customer' => ActivityLog::where('role', 'customer')->count(),
        ];

        return view('owner.monitoring', compact('logs', 'rolesCount'));
    }

    public function orders(Request $request)
    {
        $query = Order::with(['driver', 'operator', 'package'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('package')) {
            $query->where('package_code', $request->input('package'));
        }

        if ($request->filled('discrepancy') && $request->input('discrepancy') === 'yes') {
            $query->where('item_discrepancy', '!=', 0);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('owner.orders', compact('orders'));
    }
}

