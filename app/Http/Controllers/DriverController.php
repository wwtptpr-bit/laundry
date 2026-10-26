<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    public function index()
    {
        $driver = Auth::user();

        // 1. Notification Queue: Orders marked 'siap_diantar' by Operator
        $deliveryQueue = Order::with(['package'])
            ->whereIn('status', ['siap_diantar', 'dalam_pengantaran'])
            ->orderBy('priority_level', 'desc')
            ->orderBy('updated_at', 'asc')
            ->get();

        // 2. Pickup Queue: Orders waiting to be picked up
        $pickupQueue = Order::with(['package'])
            ->where('status', 'menunggu_penjemputan')
            ->orderBy('priority_level', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        // 3. Drop Queue: Laundry that has been picked up and needs to be dropped at laundry
        $dropQueue = Order::with(['package'])
            ->where('status', 'telah_diambil')
            ->orderBy('picked_up_at', 'asc')
            ->get();

        // 4. Completed History by this driver
        $completedOrders = Order::with(['package'])
            ->where('status', 'telah_diantar')
            ->latest('delivered_at')
            ->take(10)
            ->get();

        $packages = Package::where('is_active', true)->orderBy('priority_level', 'asc')->get();

        $stats = [
            'ready_to_deliver_count' => $deliveryQueue->count(),
            'needs_pickup_count' => $pickupQueue->count(),
            'needs_drop_count' => $dropQueue->count(),
            'total_delivered' => Order::where('status', 'telah_diantar')->where('driver_id', $driver->id)->count(),
        ];

        return view('supir.dashboard', compact('deliveryQueue', 'pickupQueue', 'dropQueue', 'completedOrders', 'stats', 'packages'));
    }

    public function createPickup(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'package_code' => 'required|exists:packages,code',
            'driver_item_count' => 'required|integer|min:1',
            'weight' => 'nullable|numeric|min:0.5',
            'driver_notes' => 'nullable|string|max:500',
        ]);

        $driver = Auth::user();
        $package = Package::where('code', $validated['package_code'])->firstOrFail();
        $weight = $validated['weight'] ?? 3.0;
        $orderCode = 'CLN-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $estimatedHours = $package->estimated_hours ?: 24;

        $order = Order::create([
            'order_code' => $orderCode,
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
            'driver_id' => $driver->id,
            'driver_item_count' => $validated['driver_item_count'],
            'driver_notes' => $validated['driver_notes'] ?? null,
            'picked_up_at' => Carbon::now(),
            'status' => 'telah_diambil',
            'estimated_completion_at' => Carbon::now()->addHours($estimatedHours),
            'payment_status' => 'unpaid',
        ]);

        ActivityLog::log(
            $driver,
            'DRIVER_PICKUP',
            'Supir Ambil & Input Laundry Langsung',
            "Supir {$driver->name} mengambil & menginput {$validated['driver_item_count']} potong cucian dari {$validated['customer_name']} ({$orderCode}).",
            $order,
            ['item_count' => $validated['driver_item_count'], 'weight' => $weight]
        );

        return redirect()->route('supir.dashboard')->with('success', "Pesanan baru {$orderCode} berhasil dibuat & diinput {$validated['driver_item_count']} potong cucian! Sekarang ada di daftar Drop Laundry.");
    }

    public function pickup(Request $request, Order $order)
    {
        $validated = $request->validate([
            'driver_item_count' => 'required|integer|min:1',
            'weight' => 'nullable|numeric|min:0.5',
            'driver_notes' => 'nullable|string|max:500',
        ]);

        $driver = Auth::user();

        $weight = $validated['weight'] ?? $order->weight;
        $totalPrice = $weight * $order->price_per_kg;

        $order->update([
            'driver_id' => $driver->id,
            'driver_item_count' => $validated['driver_item_count'],
            'weight' => $weight,
            'total_price' => $totalPrice,
            'driver_notes' => $validated['driver_notes'] ?? null,
            'picked_up_at' => Carbon::now(),
            'status' => 'telah_diambil',
        ]);

        ActivityLog::log(
            $driver,
            'DRIVER_PICKUP',
            'Supir Mengambil Laundry',
            "Supir {$driver->name} telah mengambil {$validated['driver_item_count']} potong cucian dari pelanggan {$order->customer_name} ({$order->order_code}). Berat: {$weight} kg.",
            $order,
            ['item_count' => $validated['driver_item_count'], 'weight' => $weight]
        );

        return redirect()->back()->with('success', "Status pesanan {$order->order_code} diperbarui: Telah Diambil ({$validated['driver_item_count']} potong). Data telah diteruskan ke Admin, Owner, Operator, dan Customer.");
    }

    public function drop(Request $request, Order $order)
    {
        $driver = Auth::user();

        $order->update([
            'dropped_at' => Carbon::now(),
            'status' => 'siap_dikerjakan', // Automatically ready for operator to work on
        ]);

        ActivityLog::log(
            $driver,
            'DRIVER_DROP',
            'Pakaian Tiba di Workshop Laundry',
            "Supir {$driver->name} telah melakukan drop pakaian {$order->order_code} di laundry. Status kini: Siap Dikerjakan Operator.",
            $order,
            ['dropped_at' => Carbon::now()->toDateTimeString()]
        );

        return redirect()->back()->with('success', "Pesanan {$order->order_code} berhasil di-drop di tempat laundry. Operator akan segera memproses.");
    }

    public function startDelivery(Request $request, Order $order)
    {
        $driver = Auth::user();

        $order->update([
            'driver_id' => $driver->id,
            'status' => 'dalam_pengantaran',
        ]);

        ActivityLog::log(
            $driver,
            'DRIVER_START_DELIVERY',
            'Pengantaran Dimulai',
            "Supir {$driver->name} membawa pesanan {$order->order_code} untuk diantar ke {$order->customer_name}.",
            $order
        );

        return redirect()->back()->with('success', "Pesanan {$order->order_code} sekarang dalam status pengantaran menuju pelanggan.");
    }

    public function deliver(Request $request, Order $order)
    {
        $driver = Auth::user();

        $order->update([
            'driver_id' => $driver->id,
            'status' => 'telah_diantar',
            'delivered_at' => Carbon::now(),
            'actual_completion_at' => Carbon::now(),
        ]);

        ActivityLog::log(
            $driver,
            'DRIVER_DELIVER',
            'Pesanan Berhasil Diantar',
            "Supir {$driver->name} telah mengantarkan pesanan {$order->order_code} ke alamat {$order->customer_address}. Laundry selesai!",
            $order,
            ['delivered_at' => Carbon::now()->toDateTimeString()]
        );

        return redirect()->back()->with('success', "Pesanan {$order->order_code} berhasil diantar kepada pelanggan! Status telah diperbarui untuk Owner, Admin, dan Customer.");
    }
}

