@extends('layouts.app')

@section('title', 'Lacak Status Pesanan Laundry - CROWN & LUCKY')

@section('content')
<div class="py-12 px-4 bg-background min-h-[80vh]">
    <div class="container mx-auto max-w-4xl">
        <!-- Search Header -->
        <div class="text-center mb-10">
            <div class="inline-block px-3 py-1 bg-secondary rounded-full text-xs font-bold text-secondary-foreground mb-2">LIVE TRACKING</div>
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-foreground">
                Lacak Status <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Pesanan Laundry</span>
            </h1>
            <p class="text-gray-500 text-sm mt-2">
                Masukkan kode pesanan Anda untuk memantau proses pengerjaan, jumlah pakaian, dan estimasi waktu selesai secara realtime.
            </p>

            <!-- Search Bar -->
            <form action="{{ route('public.track') }}" method="GET" class="mt-6 max-w-md mx-auto flex gap-2">
                <input type="text" name="code" value="{{ $searchCode }}" placeholder="Contoh: CLN-20261023-0101" required class="flex-1 px-4 py-3 rounded-2xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm bg-white shadow-sm font-mono uppercase">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-3 rounded-2xl font-bold text-sm shadow transition-colors flex items-center gap-1.5">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span>Cari</span>
                </button>
            </form>
        </div>

        @if($order)
            <!-- Order Details Card -->
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-border space-y-8">
                <!-- Header Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-border gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">Kode Pesanan:</span>
                            <span class="font-mono font-bold text-lg text-primary">{{ $order->order_code }}</span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $order->priority_badge_class }}">
                                {{ $order->package_name }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-400 mt-1">Dipesan pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                    </div>
                    <div>
                        <span class="inline-block text-xs font-bold px-4 py-1.5 rounded-full border {{ $order->status_badge_class }}">
                            Status: {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Recalculated Prediction Notice if Delayed by Operator -->
                @if($order->status === 'pending' || $order->delay_minutes > 0)
                    <div class="bg-rose-50 border-2 border-rose-200 rounded-2xl p-5 text-rose-900 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-sm">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                            <span>Pemberitahuan Penundaan oleh Operator (Prediksi Waktu Selesai Dikaji Ulang)</span>
                        </div>
                        <p class="text-xs leading-relaxed text-rose-800">
                            <strong>Alasan Penundaan:</strong> {{ $order->delay_reason ?? 'Pengerjaan membutuhkan perlakuan khusus untuk menjaga kualitas pakaian.' }}
                        </p>
                        <div class="text-xs font-semibold text-rose-950 pt-1">
                            Prediksi Waktu Selesai Baru: <strong>{{ $order->estimated_completion_at ? $order->estimated_completion_at->translatedFormat('d F Y, H:i') : 'Menyesuaikan' }} WIB</strong>
                        </div>
                    </div>
                @else
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-emerald-900">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xs text-emerald-700 font-medium">Prediksi Pesanan Selesai:</div>
                                <div class="text-sm font-bold text-emerald-950">
                                    {{ $order->estimated_completion_at ? $order->estimated_completion_at->translatedFormat('l, d F Y - H:i') : 'Dalam perhitungan' }} WIB
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-bold bg-white px-3 py-1.5 rounded-xl border border-emerald-200 text-emerald-700">
                            Prioritas Level {{ $order->priority_level }}/4
                        </span>
                    </div>
                @endif

                <!-- Visual Progress Stepper -->
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-6 flex items-center gap-2">
                        <i data-lucide="git-commit" class="w-4 h-4 text-primary"></i>
                        <span>Tahapan Pengerjaan Laundry:</span>
                    </h3>

                    @php
                        $steps = [
                            ['key' => 'menunggu_penjemputan', 'label' => 'Order Diterima', 'desc' => 'Menunggu supir jemput'],
                            ['key' => 'telah_diambil', 'label' => 'Diambil Supir', 'desc' => ($order->driver_item_count ? $order->driver_item_count . ' potong dihitung' : 'Pakaian diambil')],
                            ['key' => 'siap_dikerjakan', 'label' => 'Drop di Laundry', 'desc' => 'Tiba di workshop'],
                            ['key' => 'proses', 'label' => 'Dicuci & Disetrika', 'desc' => ($order->status === 'pending' ? 'Tertunda sementara' : 'Sedang diproses operator')],
                            ['key' => 'siap_diantar', 'label' => 'Siap Diantar', 'desc' => 'Supir bersiap meluncur'],
                            ['key' => 'telah_diantar', 'label' => 'Telah Diterima', 'desc' => 'Selesai diantar ke Anda'],
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

                        $currentIdx = $statusOrder[$order->status] ?? 1;
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                        @foreach($steps as $idx => $step)
                            @php
                                $stepNum = $idx + 1;
                                $isDone = $currentIdx > $stepNum;
                                $isCurrent = $currentIdx === $stepNum;
                            @endphp
                            <div class="p-3.5 rounded-2xl border text-center transition-all 
                                {{ $isCurrent ? 'bg-primary/10 border-primary ring-2 ring-primary/20' : ($isDone ? 'bg-emerald-50/50 border-emerald-200' : 'bg-gray-50 border-gray-200 opacity-60') }}">
                                <div class="w-7 h-7 mx-auto rounded-full flex items-center justify-center text-xs font-bold mb-2
                                    {{ $isCurrent ? 'bg-primary text-white animate-pulse' : ($isDone ? 'bg-emerald-500 text-white' : 'bg-gray-300 text-gray-700') }}">
                                    @if($isDone)
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    @else
                                        {{ $stepNum }}
                                    @endif
                                </div>
                                <div class="text-xs font-bold text-foreground leading-tight">{{ $step['label'] }}</div>
                                <div class="text-[10px] text-gray-500 mt-1 leading-snug">{{ $step['desc'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Transparency: Item Counts & Discrepancy Checks -->
                <div class="bg-gray-50 rounded-2xl p-5 border border-border">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Transparansi Penghitungan Pakaian:</h4>
                    <div class="grid sm:grid-cols-3 gap-4 text-xs">
                        <div class="bg-white p-3 rounded-xl border border-border">
                            <span class="text-gray-500 block">Input Supir Saat Jemput:</span>
                            <span class="text-base font-bold text-foreground">
                                {{ $order->driver_item_count ? $order->driver_item_count . ' Potong' : 'Belum dihitung supir' }}
                            </span>
                            @if($order->weight)
                                <span class="text-[11px] text-gray-400 block mt-0.5">Berat: {{ $order->weight }} kg</span>
                            @endif
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-border">
                            <span class="text-gray-500 block">Hitung Ulang Operator:</span>
                            <span class="text-base font-bold text-foreground">
                                {{ $order->operator_item_count ? $order->operator_item_count . ' Potong' : 'Menunggu pengerjaan' }}
                            </span>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-border">
                            <span class="text-gray-500 block">Status Selisih Barang:</span>
                            @if($order->operator_item_count === null)
                                <span class="text-xs text-gray-400 italic">Belum dihitung ulang</span>
                            @elseif($order->item_discrepancy === 0)
                                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1 mt-1">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Cocok (0 Selisih)
                                </span>
                            @else
                                <span class="text-xs font-bold text-rose-600 flex items-center gap-1 mt-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i> Terdapat Selisih ({{ $order->item_discrepancy > 0 ? '+'.$order->item_discrepancy : $order->item_discrepancy }} pcs)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Details & Total Price -->
                <div class="grid sm:grid-cols-2 gap-4 text-xs pt-4 border-t border-border">
                    <div>
                        <span class="text-gray-500 block">Nama Penerima & Alamat:</span>
                        <div class="font-bold text-sm text-foreground mt-0.5">{{ $order->customer_name }}</div>
                        <div class="text-gray-600 mt-1 leading-relaxed">{{ $order->customer_address }}</div>
                        <div class="text-gray-500 mt-1">No. WhatsApp: {{ $order->customer_phone }}</div>
                    </div>
                    <div class="sm:text-right bg-secondary/40 p-4 rounded-2xl border border-border flex flex-col justify-center">
                        <span class="text-gray-500 block">Rincian Biaya:</span>
                        <div class="text-xl font-serif font-bold text-primary mt-1">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                        <div class="text-[11px] text-gray-500 mt-0.5">
                            {{ $order->weight }} kg x Rp {{ number_format($order->price_per_kg, 0, ',', '.') }} ({{ $order->package_name }})
                        </div>
                        <div class="mt-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $order->payment_status === 'paid' ? 'LUNAS' : 'BELUM DIBAYAR (Bayar di Tempat)' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="flex flex-wrap gap-3 pt-4 border-t border-border">
                    <a href="https://wa.me/6281234567890?text=Halo%20CROWN%20%26%20LUCKY,%20saya%20ingin%20tanya%20status%20pesanan%20{{ $order->order_code }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-5 py-2.5 rounded-xl text-xs flex items-center gap-1.5 shadow transition-colors">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Hubungi CS via WhatsApp
                    </a>
                    <button onclick="window.print()" class="border border-border hover:bg-gray-50 px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                        <i data-lucide="printer" class="w-4 h-4"></i> Cetak Resi
                    </button>
                    <a href="{{ route('home') }}" class="ml-auto text-xs text-primary hover:underline flex items-center gap-1 font-semibold">
                        Kembali ke Beranda &rarr;
                    </a>
                </div>
            </div>
        @elseif($searchCode)
            <div class="bg-white rounded-3xl p-8 text-center border border-border shadow-md max-w-md mx-auto space-y-4">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <h3 class="font-bold text-lg text-foreground">Pesanan Tidak Ditemukan</h3>
                <p class="text-xs text-gray-500">Kode pesanan <strong>"{{ $searchCode }}"</strong> tidak cocok dengan data kami. Mohon pastikan kode yang Anda masukkan sudah benar.</p>
                <a href="{{ route('home') }}#order" class="inline-block bg-primary text-white font-bold px-6 py-2.5 rounded-xl text-xs shadow hover:bg-primary-hover transition-colors">
                    Buat Pesanan Baru
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

