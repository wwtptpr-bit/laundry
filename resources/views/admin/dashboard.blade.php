@extends('layouts.dashboard')

@section('title', 'Dashboard Admin (Monitoring Read-Only)')

@section('content')
<div class="space-y-8">
    <!-- Notice Banner for Admin -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                🛡️ Akun Admin (Mode Pantau / Read-Only)
            </div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-white/80 text-xs md:text-sm mt-1 max-w-xl">
                Sesuai ketentuan sistem, <strong>Admin hanya menampilkan apa yang tampil pada Owner</strong> untuk kebutuhan monitoring dan verifikasi, <strong>tetapi tidak dapat mengubah data apapun</strong>.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-white/10 text-white font-semibold px-4 py-2 rounded-xl text-xs border border-white/20">
                🔒 Mode Akses: Read-Only
            </span>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-border shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Total Pendapatan</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-bold font-serif text-foreground">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-emerald-600 font-medium mt-1">Data sinkron dengan Owner</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-border shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Pesanan Aktif Berjalan</span>
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-bold font-serif text-foreground">
                {{ $stats['active_orders'] }}
            </div>
            <div class="text-[11px] text-blue-600 font-medium mt-1">Dalam proses opersional</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-border shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Peringatan Selisih Barang</span>
                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-bold font-serif {{ $stats['discrepancy_count'] > 0 ? 'text-rose-600' : 'text-foreground' }}">
                {{ $stats['discrepancy_count'] }}
            </div>
            <div class="text-[11px] {{ $stats['discrepancy_count'] > 0 ? 'text-rose-600 font-bold' : 'text-gray-400' }} mt-1">
                {{ $stats['discrepancy_count'] > 0 ? 'Peringatan selisih barang tercatat' : 'Semua hitungan cocok' }}
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-border shadow-sm">
            <div class="flex items-center justify-between text-gray-500 text-xs font-medium mb-2">
                <span>Total Seluruh Pesanan</span>
                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i data-lucide="package-check" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-bold font-serif text-foreground">
                {{ $stats['total_orders'] }}
            </div>
            <div class="text-[11px] text-purple-600 font-medium mt-1">Riwayat transaksi sistem</div>
        </div>
    </div>

    <!-- Read-Only Price Table Preview for Admin -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-4 border-b border-border">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-serif font-bold text-foreground">Daftar Harga Paket Layanan (Read-Only)</h2>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-700">Hanya Lihat</span>
                </div>
                <p class="text-xs text-gray-500">Admin hanya dapat melihat harga aktif. Tombol ubah dinonaktifkan khusus untuk Owner.</p>
            </div>
            <span class="text-xs font-semibold text-gray-400 italic">🔒 Tidak dapat diubah oleh Admin</span>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($packages as $pkg)
                <div class="bg-background rounded-2xl p-4 border border-border space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm text-foreground">{{ $pkg->name }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-200 text-gray-700 uppercase">{{ $pkg->code }}</span>
                    </div>

                    <div class="pt-2">
                        <div class="text-[11px] text-gray-500">Harga Per Kg:</div>
                        <div class="text-xl font-bold text-primary font-serif">
                            Rp {{ number_format($pkg->price_per_kg, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="text-[10px] text-gray-500 flex justify-between pt-1 border-t border-border">
                        <span>Estimasi: <strong>{{ $pkg->estimated_hours }} Jam</strong></span>
                        <span>Prioritas: <strong>Level {{ $pkg->priority_level }}/4</strong></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Live Monitoring Stream of 4 Roles -->
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Live Activity Stream (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-border">
                <div>
                    <h2 class="text-lg font-serif font-bold text-foreground flex items-center gap-2">
                        <i data-lucide="activity" class="w-5 h-5 text-blue-600"></i>
                        <span>Audit Log Monitoring (Pantau Aktivitas)</span>
                    </h2>
                    <p class="text-xs text-gray-500">Riwayat log kegiatan Supir, Operator, Pelanggan, dan Owner.</p>
                </div>
                <a href="{{ route('admin.monitoring') }}" class="text-xs font-bold text-primary hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="p-3.5 rounded-2xl border border-border bg-background hover:bg-white transition-colors flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-sm font-bold
                            {{ $log->role === 'supir' ? 'bg-emerald-100 text-emerald-700' : ($log->role === 'oprator' ? 'bg-purple-100 text-purple-700' : ($log->role === 'owner' ? 'bg-amber-100 text-amber-700' : 'bg-teal-100 text-teal-700')) }}">
                            @if($log->role === 'supir') 🚚
                            @elseif($log->role === 'oprator') ⚙️
                            @elseif($log->role === 'owner') 👑
                            @else 👤
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-xs text-foreground">{{ $log->title }}</span>
                                <span class="text-[10px] text-gray-400 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ $log->description }}</p>
                            <div class="flex items-center gap-2 mt-1.5 text-[10px]">
                                <span class="px-2 py-0.5 rounded-md font-bold uppercase {{ $log->role_badge_class }}">
                                    {{ $log->role }}
                                </span>
                                <span class="text-gray-400">Oleh: <strong>{{ $log->user_name }}</strong></span>
                                @if($log->order_code)
                                    <span class="text-primary font-mono font-semibold">{{ $log->order_code }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-xs">Belum ada aktivitas tercatat.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders (1 col) -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-border">
                <h2 class="text-base font-serif font-bold text-foreground">Pesanan Masuk</h2>
                <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-primary hover:underline">Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <div class="p-3 rounded-2xl border border-border bg-background space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-xs text-primary">{{ $order->order_code }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-700 font-semibold">{{ $order->customer_name }} ({{ $order->package_name }})</div>
                        <div class="flex items-center justify-between text-[11px] text-gray-500 pt-1 border-t border-border/60">
                            <span>Input Supir: <strong>{{ $order->driver_item_count ?? '-' }} pcs</strong></span>
                            <span>Operator: <strong>{{ $order->operator_item_count ?? '-' }} pcs</strong></span>
                        </div>

                        @if($order->hasDiscrepancy())
                            <div class="text-[10px] font-bold text-rose-600 bg-rose-50 p-1.5 rounded-lg border border-rose-200">
                                ⚠️ Selisih: {{ $order->item_discrepancy > 0 ? '+'.$order->item_discrepancy : $order->item_discrepancy }} pcs
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-xs">Belum ada pesanan masuk.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

