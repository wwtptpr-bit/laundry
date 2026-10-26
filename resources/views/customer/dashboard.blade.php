@extends('layouts.dashboard')

@section('title', 'Pesanan Aktif & Prediksi Selesai (Customer)')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-700 to-emerald-700 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                👤 Akun Pelanggan (Customer)
            </div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold">Halo, {{ Auth::user()->name }}</h1>
            <p class="text-white/80 text-xs md:text-sm mt-1 max-w-xl">
                Pantau proses cucian Anda secara realtime, cek prediksi waktu selesai, dan pilih paket layanan: <strong>Reguler, VIP, VVIP, atau Prioritas</strong>.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('customer.order.create') }}" class="bg-white hover:bg-emerald-50 text-emerald-800 font-bold px-5 py-3 rounded-2xl text-xs shadow-md transition-colors flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Pesan Laundry Baru</span>
            </a>
            <a href="{{ route('customer.history') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold px-4 py-3 rounded-2xl text-xs border border-white/30 transition-colors flex items-center gap-1.5">
                <i data-lucide="history" class="w-4 h-4"></i>
                <span>Menu History</span>
            </a>
        </div>
    </div>

    <!-- Active Orders Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-serif font-bold text-foreground">Pesanan Laundry Anda yang Sedang Berjalan</h2>
                <p class="text-xs text-gray-500">Status pengerjaan diperbarui langsung oleh supir dan operator workshop.</p>
            </div>
            <span class="text-xs font-bold bg-teal-100 text-teal-800 px-3 py-1 rounded-full">
                {{ $activeOrders->count() }} Pesanan Aktif
            </span>
        </div>

        @forelse($activeOrders as $ord)
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-md space-y-6">
                <!-- Order Top Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-border gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-mono font-bold text-base text-primary">{{ $ord->order_code }}</span>
                        <span class="text-xs font-bold px-3 py-1 rounded-full {{ $ord->priority_badge_class }}">
                            {{ $ord->package_name }}
                        </span>
                        <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $ord->status_badge_class }}">
                            {{ $ord->status_label }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-400">
                        Dipesan: {{ $ord->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </div>
                </div>

                <!-- PREDIKSI WAKTU SELESAI (DENGAN KAJI ULANG PENUNDAAN OPERATOR) -->
                @if($ord->status === 'pending' || $ord->delay_minutes > 0)
                    <div class="bg-rose-50 border-2 border-rose-300 rounded-2xl p-4 md:p-5 text-rose-900 space-y-2 animate-pulse">
                        <div class="flex items-center gap-2 font-bold text-sm text-rose-700">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                            <span>Pemberitahuan: Prediksi Waktu Selesai Dikaji Ulang Akibat Penundaan Operator</span>
                        </div>
                        <p class="text-xs leading-relaxed text-rose-800">
                            <strong>Alasan Penundaan:</strong> {{ $ord->delay_reason ?? 'Pengerjaan membutuhkan perlakuan khusus untuk menjaga kualitas kebersihan pakaian.' }}
                        </p>
                        <div class="text-xs font-bold text-rose-950 pt-1">
                            Prediksi Waktu Selesai Terkini: 
                            <span class="text-sm underline font-extrabold">{{ $ord->estimated_completion_at ? $ord->estimated_completion_at->translatedFormat('l, d F Y - H:i') : '-' }} WIB</span>
                        </div>
                    </div>
                @else
                    <div class="bg-emerald-50/70 border border-emerald-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-emerald-950">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="text-xs text-emerald-700 font-semibold block">Prediksi Kapan Pesanan Selesai:</span>
                                <span class="text-base font-bold text-emerald-950 block">
                                    {{ $ord->estimated_completion_at ? $ord->estimated_completion_at->translatedFormat('l, d F Y - H:i') : 'Menunggu konfirmasi supir' }} WIB
                                </span>
                            </div>
                        </div>
                        <div class="text-xs text-emerald-700 bg-white px-3 py-1.5 rounded-xl border border-emerald-200 font-semibold">
                            Urutan Antrian: <strong>Level {{ $ord->priority_level }}/4 ({{ $ord->package_name }})</strong>
                        </div>
                    </div>
                @endif

                <!-- Stepper Progress Bar -->
                @php
                    $steps = [
                        ['key' => 'menunggu_penjemputan', 'label' => 'Order Masuk', 'desc' => 'Menunggu supir jemput'],
                        ['key' => 'telah_diambil', 'label' => 'Diambil Supir', 'desc' => ($ord->driver_item_count ? $ord->driver_item_count . ' potong dihitung' : 'Penjemputan')],
                        ['key' => 'siap_dikerjakan', 'label' => 'Drop di Laundry', 'desc' => 'Tiba di workshop'],
                        ['key' => 'proses', 'label' => 'Sedang Diproses', 'desc' => ($ord->status === 'pending' ? 'Tertunda operator' : 'Cuci & setrika')],
                        ['key' => 'siap_diantar', 'label' => 'Siap Diantar', 'desc' => 'Menunggu supir meluncur'],
                        ['key' => 'telah_diantar', 'label' => 'Selesai', 'desc' => 'Diterima pelanggan'],
                    ];

                    $statusOrder = [
                        'menunggu_penjemputan' => 1,
                        'telah_diambil' => 2,
                        'telah_drop' => 3,
                        'siap_dikerjakan' => 3,
                        'proses' => 4,
                        'pending' => 4,
                        'siap_diantar' => 5,
                        'dalam_pengantaran' => 5,
                        'telah_diantar' => 6,
                    ];

                    $currentIdx = $statusOrder[$ord->status] ?? 1;
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-6 gap-2.5">
                    @foreach($steps as $idx => $step)
                        @php
                            $stepNum = $idx + 1;
                            $isDone = $currentIdx > $stepNum;
                            $isCurrent = $currentIdx === $stepNum;
                        @endphp
                        <div class="p-3 rounded-2xl border text-center 
                            {{ $isCurrent ? 'bg-primary/10 border-primary ring-2 ring-primary/20' : ($isDone ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200 opacity-60') }}">
                            <div class="w-6 h-6 mx-auto rounded-full flex items-center justify-center text-xs font-bold mb-1.5
                                {{ $isCurrent ? 'bg-primary text-white animate-pulse' : ($isDone ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-700') }}">
                                @if($isDone)
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                @else
                                    {{ $stepNum }}
                                @endif
                            </div>
                            <div class="text-xs font-bold text-foreground leading-tight">{{ $step['label'] }}</div>
                            <div class="text-[10px] text-gray-500 mt-0.5 leading-snug">{{ $step['desc'] }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Transparency Details -->
                <div class="grid sm:grid-cols-3 gap-3 text-xs bg-gray-50 p-4 rounded-2xl border border-border">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Total Pakaian Dihitung Supir:</span>
                        <span class="font-bold text-sm text-foreground">
                            {{ $ord->driver_item_count ? $ord->driver_item_count . ' Potong' : 'Belum dihitung supir' }}
                        </span>
                        <span class="text-[11px] text-gray-500 block">Berat: {{ $ord->weight }} kg</span>
                    </div>

                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Alamat Pengantaran:</span>
                        <span class="text-xs text-gray-700 font-medium block leading-snug">{{ $ord->customer_address }}</span>
                    </div>

                    <div class="sm:text-right">
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Total Estimasi Biaya:</span>
                        <span class="text-base font-bold font-serif text-primary">
                            Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] block text-gray-500 uppercase">{{ $ord->payment_status === 'paid' ? 'LUNAS' : 'Bayar Saat Diantar' }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-2">
                    <a href="{{ route('public.track', ['code' => $ord->order_code]) }}" target="_blank" class="text-primary hover:underline font-semibold flex items-center gap-1">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>Buka Halaman Resi Publik</span>
                    </a>
                    <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20tanya%20pesanan%20{{ $ord->order_code }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-1">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        <span>Chat CS</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-border shadow-sm space-y-4">
                <div class="w-16 h-16 bg-secondary text-primary rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="package" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-serif font-bold text-foreground">Tidak Ada Pesanan yang Sedang Berjalan</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto">Anda belum memiliki pesanan laundry aktif. Butuh cuci bersih kilat? Buat pesanan baru sekarang!</p>
                <a href="{{ route('customer.order.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-emerald-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow hover:opacity-95">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Pesan Laundry Sekarang</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Recent History Preview -->
    @if($recentCompleted->count() > 0)
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-border">
                <h3 class="text-base font-serif font-bold text-foreground">Riwayat Pesanan Terakhir</h3>
                <a href="{{ route('customer.history') }}" class="text-xs font-bold text-primary hover:underline">
                    Buka Menu History Selengkapnya &rarr;
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                @foreach($recentCompleted as $rc)
                    <div class="bg-background p-4 rounded-2xl border border-border space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-xs text-primary">{{ $rc->order_code }}</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Selesai Diantar</span>
                        </div>
                        <div class="text-xs font-semibold text-gray-800">{{ $rc->package_name }} &bull; {{ $rc->weight }} kg</div>
                        <div class="text-xs font-bold text-primary font-serif">Rp {{ number_format($rc->total_price, 0, ',', '.') }}</div>
                        <div class="text-[10px] text-gray-400">Diantar: {{ $rc->delivered_at ? $rc->delivered_at->translatedFormat('d M Y') : '-' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

