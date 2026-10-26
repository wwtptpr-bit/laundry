<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;

class AdminController extends Controller
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

        return view('admin.dashboard', compact('stats', 'packages', 'recentLogs', 'recentOrders'));
    }

    public function prices()
    {
        $packages = Package::orderBy('priority_level', 'asc')->get();
        return view('admin.prices', compact('packages'));
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
            'owner' => ActivityLog::where('role', 'owner')->count(),
            'supir' => ActivityLog::where('role', 'supir')->count(),
            'oprator' => ActivityLog::where('role', 'oprator')->count(),
            'customer' => ActivityLog::where('role', 'customer')->count(),
        ];

        return view('admin.monitoring', compact('logs', 'rolesCount'));
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

        return view('admin.orders', compact('orders'));
    }
}

