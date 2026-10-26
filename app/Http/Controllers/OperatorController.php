<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        $operator = Auth::user();

        // 1. Priority Queue of incoming/processing orders:
        // Prioritas (4) > VVIP (3) > VIP (2) > Reguler (1)
        // Orders appear once driver picks them up ('telah_diambil'), dropped ('siap_dikerjakan'), in progress ('proses'), or pending ('pending')
        $queueOrders = Order::with(['package', 'driver'])
            ->whereIn('status', ['telah_diambil', 'siap_dikerjakan', 'proses', 'pending'])
            ->orderBy('priority_level', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Finished orders waiting for driver delivery
        $readyOrders = Order::with(['package', 'driver'])
            ->where('status', 'siap_diantar')
            ->latest('updated_at')
            ->get();

        // 3. Completed history
        $completedOrders = Order::with(['package', 'driver'])
            ->where('status', 'telah_diantar')
            ->latest('delivered_at')
            ->take(10)
            ->get();

        $stats = [
            'total_queue' => $queueOrders->count(),
            'ready_to_work' => $queueOrders->where('status', 'siap_dikerjakan')->count(),
            'in_progress' => $queueOrders->where('status', 'proses')->count(),
            'pending_delay' => $queueOrders->where('status', 'pending')->count(),
            'discrepancy_count' => Order::where('item_discrepancy', '!=', 0)->count(),
        ];

        return view('operator.dashboard', compact('queueOrders', 'readyOrders', 'completedOrders', 'stats'));
    }

    // Input Ulang Jumlah Barang (Re-count without overwriting driver input)
    public function recount(Request $request, Order $order)
    {
        $validated = $request->validate([
            'operator_item_count' => 'required|integer|min:0',
            'discrepancy_note' => 'nullable|string|max:500',
        ]);

        $operator = Auth::user();
        $operatorCount = $validated['operator_item_count'];
        $driverCount = $order->driver_item_count ?? 0;
        $discrepancy = $operatorCount - $driverCount;

        $note = $validated['discrepancy_note'];
        if ($discrepancy !== 0 && empty($note)) {
            $note = $discrepancy > 0 
                ? "Kelebihan {$discrepancy} potong (Driver: {$driverCount}, Operator: {$operatorCount})"
                : "Kekurangan " . abs($discrepancy) . " potong (Driver: {$driverCount}, Operator: {$operatorCount})";
        } elseif ($discrepancy === 0) {
            $note = "Jumlah barang cocok sempurna ({$operatorCount} potong).";
        }

        $order->update([
            'operator_id' => $operator->id,
            'operator_item_count' => $operatorCount,
            'item_discrepancy' => $discrepancy,
            'discrepancy_note' => $note,
        ]);

        $action = $discrepancy !== 0 ? 'OPERATOR_RECOUNT_DISCREPANCY' : 'OPERATOR_RECOUNT';
        $title = $discrepancy !== 0 ? '⚠️ Peringatan Selisih Barang Ditemukan' : 'Hitung Ulang Barang Sesuai';
        $desc = "Operator {$operator->name} menghitung ulang pesanan {$order->order_code}: {$operatorCount} potong vs input Supir {$driverCount} potong (Selisih: " . ($discrepancy > 0 ? "+{$discrepancy}" : "{$discrepancy}") . " potong).";

        ActivityLog::log(
            $operator,
            $action,
            $title,
            $desc,
            $order,
            ['driver_count' => $driverCount, 'operator_count' => $operatorCount, 'selisih' => $discrepancy]
        );

        if ($discrepancy !== 0) {
            return redirect()->back()->with('warning', "Perhatian: Terdapat selisih {$discrepancy} potong antara supir ({$driverCount}) dan operator ({$operatorCount}). Selisih telah dicatat dan dilaporkan ke Owner & Admin!");
        }

        return redirect()->back()->with('success', "Hitung ulang barang berhasil dicatat: {$operatorCount} potong (Sesuai dengan supir, tidak ada selisih).");
    }

    // Mulai Pengerjaan (Start Processing)
    public function start(Request $request, Order $order)
    {
        $operator = Auth::user();

        $order->update([
            'operator_id' => $operator->id,
            'status' => 'proses',
        ]);

        ActivityLog::log(
            $operator,
            'OPERATOR_START',
            'Pengerjaan Laundry Dimulai',
            "Operator {$operator->name} memulai pencucian untuk pesanan {$order->order_code} ({$order->package_name}).",
            $order
        );

        return redirect()->back()->with('success', "Status pesanan {$order->order_code} kini dalam proses pengerjaan.");
    }

    // Penundaan Proses Laundry (Postpone / Delay) with recalculated completion estimation
    public function delay(Request $request, Order $order)
    {
        $validated = $request->validate([
            'delay_reason' => 'required|string|max:500',
            'delay_hours' => 'required|numeric|min:0.5|max:72',
        ]);

        $operator = Auth::user();
        $delayMinutes = (int) ($validated['delay_hours'] * 60);

        // Recalculate estimated completion time
        $currentEstimate = $order->estimated_completion_at ?: Carbon::now()->addHours($order->estimated_hours ?: 24);
        $newEstimate = Carbon::parse($currentEstimate)->addMinutes($delayMinutes);

        $order->update([
            'operator_id' => $operator->id,
            'status' => 'pending',
            'delay_reason' => $validated['delay_reason'],
            'delay_minutes' => $order->delay_minutes + $delayMinutes,
            'delayed_at' => Carbon::now(),
            'estimated_completion_at' => $newEstimate,
        ]);

        ActivityLog::log(
            $operator,
            'OPERATOR_DELAY',
            'Penundaan Proses Laundry',
            "Operator {$operator->name} menunda pesanan {$order->order_code} selama {$validated['delay_hours']} jam. Alasan: {$validated['delay_reason']}. Prediksi selesai dikaji ulang menjadi: " . $newEstimate->translatedFormat('d M Y, H:i') . " WIB.",
            $order,
            ['reason' => $validated['delay_reason'], 'delay_hours' => $validated['delay_hours'], 'new_estimate' => $newEstimate->toDateTimeString()]
        );

        return redirect()->back()->with('info', "Pesanan {$order->order_code} ditunda ({$validated['delay_hours']} jam). Alasan & estimasi waktu baru telah diteruskan ke Owner, Admin, dan Customer.");
    }

    // Lanjutkan kembali proses yang tertunda
    public function resume(Request $request, Order $order)
    {
        $operator = Auth::user();

        $order->update([
            'operator_id' => $operator->id,
            'status' => 'proses',
        ]);

        ActivityLog::log(
            $operator,
            'OPERATOR_RESUME',
            'Proses Laundry Dilanjutkan',
            "Operator {$operator->name} melanjutkan pengerjaan pesanan {$order->order_code} yang sebelumnya tertunda.",
            $order
        );

        return redirect()->back()->with('success', "Pesanan {$order->order_code} kembali dilanjutkan dalam proses.");
    }

    // Selesaikan Pengerjaan (Mark as Ready to Deliver)
    // Sends info to Owner, Admin, Customer, and triggers Driver delivery queue!
    public function finish(Request $request, Order $order)
    {
        $operator = Auth::user();

        $order->update([
            'operator_id' => $operator->id,
            'status' => 'siap_diantar',
            'actual_completion_at' => Carbon::now(),
        ]);

        ActivityLog::log(
            $operator,
            'OPERATOR_FINISH',
            'Pengerjaan Selesai - Siap Diantar',
            "Operator {$operator->name} telah menyelesaikan pencucian pesanan {$order->order_code}. Status berubah menjadi: Siap Diantar. Notifikasi dikirimkan ke supir untuk pengantaran.",
            $order,
            ['completed_at' => Carbon::now()->toDateTimeString()]
        );

        return redirect()->back()->with('success', "Pesanan {$order->order_code} berhasil diselesaikan! Notifikasi pengantaran telah muncul di dashboard supir.");
    }
}

