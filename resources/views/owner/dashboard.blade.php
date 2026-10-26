@extends('layouts.dashboard')

@section('title', 'Dashboard Owner')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header & Banner -->
    <div class="bg-gradient-to-r from-primary to-emerald-700 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                👑 Akun Pemilik (Owner)
            </div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-white/80 text-xs md:text-sm mt-1 max-w-xl">
                Sebagai <strong>Owner</strong>, Anda memiliki hak penuh untuk <strong>merubah harga paket di dashboard publik</strong> serta <strong>memonitor seluruh aktivitas dari 4 role lainnya</strong> (Admin, Supir, Operator, Customer).
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('owner.prices') }}" class="bg-amber-400 hover:bg-amber-300 text-amber-950 font-bold px-4 py-2.5 rounded-xl text-xs shadow transition-colors flex items-center gap-1.5">
                <i data-lucide="tag" class="w-4 h-4"></i>
                <span>Kelola Harga Paket Publik</span>
            </a>
            <a href="{{ route('owner.monitoring') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold px-4 py-2.5 rounded-xl text-xs border border-white/30 transition-colors flex items-center gap-1.5">
                <i data-lucide="activity" class="w-4 h-4"></i>
                <span>Pantau 4 Role</span>
            </a>
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
            <div class="text-[11px] text-emerald-600 font-medium mt-1">Akumulasi pesanan selesai</div>
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
            <div class="text-[11px] text-blue-600 font-medium mt-1">Sedang dijemput / dicuci / diantar</div>
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
                {{ $stats['discrepancy_count'] > 0 ? 'Perlu perhatian (Supir vs Operator)' : 'Semua hitungan cocok' }}
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
            <div class="text-[11px] text-purple-600 font-medium mt-1">Total riwayat transaksi</div>
        </div>
    </div>

    <!-- Quick Price Edit for Public Dashboard -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-border gap-2 mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-serif font-bold text-foreground">Ubah Harga Paket Layanan (Tampil di Publik)</h2>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800">Khusus Owner</span>
                </div>
                <p class="text-xs text-gray-500">Perubahan harga di bawah ini langsung otomatis tercermin pada halaman utama / landing page.</p>
            </div>
            <a href="{{ route('home') }}#pricing" target="_blank" class="text-xs text-primary hover:underline font-semibold flex items-center gap-1">
                <span>Lihat Tampilan Publik</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($packages as $pkg)
                <div class="bg-background rounded-2xl p-4 border border-border space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm text-foreground">{{ $pkg->name }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-200 text-gray-700 uppercase">{{ $pkg->code }}</span>
                    </div>

                    <form action="{{ route('owner.prices.update', $pkg->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="name" value="{{ $pkg->name }}">
                        <input type="hidden" name="estimated_hours" value="{{ $pkg->estimated_hours }}">
                        
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 mb-1">Harga Per Kg (Rp):</label>
                            <input type="number" name="price_per_kg" value="{{ (int)$pkg->price_per_kg }}" min="100" step="500" required class="w-full px-3 py-2 rounded-xl border border-border bg-white text-sm font-bold text-primary focus:ring-2 focus:ring-primary outline-none">
                        </div>

                        <div class="text-[10px] text-gray-500 flex justify-between">
                            <span>Estimasi Waktu:</span>
                            <strong>{{ $pkg->estimated_hours }} Jam</strong>
                        </div>

                        <button type="submit" class="w-full py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center gap-1.5">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i>
                            <span>Simpan Harga</span>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Live Monitoring Stream of 4 Roles (Admin, Supir, Operator, Customer) -->
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Live Activity Stream (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-border">
                <div>
                    <h2 class="text-lg font-serif font-bold text-foreground flex items-center gap-2">
                        <i data-lucide="activity" class="w-5 h-5 text-emerald-600"></i>
                        <span>Monitoring Realtime 4 Role Lainnya</span>
                    </h2>
                    <p class="text-xs text-gray-500">Audit trail aktivitas Admin, Supir, Operator, dan Customer.</p>
                </div>
                <a href="{{ route('owner.monitoring') }}" class="text-xs font-bold text-primary hover:underline">
                    Lihat Semua Log &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="p-3.5 rounded-2xl border border-border bg-background hover:bg-white transition-colors flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-sm font-bold
                            {{ $log->role === 'supir' ? 'bg-emerald-100 text-emerald-700' : ($log->role === 'oprator' ? 'bg-purple-100 text-purple-700' : ($log->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-teal-100 text-teal-700')) }}">
                            @if($log->role === 'supir') 🚚
                            @elseif($log->role === 'oprator') ⚙️
                            @elseif($log->role === 'admin') 🛡️
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

        <!-- Recent Orders & Discrepancies (1 col) -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-border">
                <h2 class="text-base font-serif font-bold text-foreground">Pesanan Terbaru</h2>
                <a href="{{ route('owner.orders') }}" class="text-xs font-bold text-primary hover:underline">Semua</a>
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
                            <span>Hitung Operator: <strong>{{ $order->operator_item_count ?? '-' }} pcs</strong></span>
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

