@extends('layouts.dashboard')

@section('title', 'Menu History (Riwayat Pesanan Selesai)')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" x-data="{ receiptModal: false, selectedReceipt: {} }">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-bold uppercase tracking-wider mb-2">
                <i data-lucide="history" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Menu History</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-foreground">Riwayat Laundry Selesai</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar seluruh pakaian Anda yang telah selesai diproses dan diantarkan ke alamat tujuan.</p>
        </div>
        <div class="flex items-center gap-4 text-xs">
            <div class="bg-secondary/60 p-3 rounded-2xl border border-border">
                <span class="text-gray-500 block">Total Pesanan Selesai:</span>
                <span class="text-base font-bold text-foreground font-serif">{{ $stats['total_completed'] }} Pesanan</span>
            </div>
            <div class="bg-secondary/60 p-3 rounded-2xl border border-border">
                <span class="text-gray-500 block">Total Pengeluaran:</span>
                <span class="text-base font-bold text-primary font-serif">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-2xl p-4 border border-border shadow-sm">
        <form method="GET" action="{{ route('customer.history') }}" class="flex gap-2 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pesanan / paket..." class="flex-1 px-4 py-2 rounded-xl border border-border text-xs focus:ring-2 focus:ring-primary outline-none">
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-primary-hover flex items-center gap-1.5">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Cari</span>
            </button>
            @if(request('search'))
                <a href="{{ route('customer.history') }}" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- History Cards List -->
    <div class="space-y-4">
        @forelse($orders as $ord)
            <div class="bg-white rounded-3xl p-6 border border-border shadow-sm hover:shadow-md transition-shadow space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-border gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-bold text-sm text-primary">{{ $ord->order_code }}</span>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $ord->priority_badge_class }}">
                            {{ $ord->package_name }}
                        </span>
                        <span class="text-xs font-bold px-3 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">
                            Selesai & Telah Diantar
                        </span>
                    </div>
                    <div class="text-xs text-gray-400">
                        Diantar pada: <strong>{{ $ord->delivered_at ? $ord->delivered_at->translatedFormat('d F Y, H:i') : '-' }} WIB</strong>
                    </div>
                </div>

                <div class="grid sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Jumlah Pakaian:</span>
                        <span class="font-bold text-sm text-foreground">
                            {{ $ord->driver_item_count ?? $ord->operator_item_count }} Potong
                        </span>
                        <span class="text-[11px] text-gray-500 block">Berat: {{ $ord->weight }} kg</span>
                    </div>

                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Supir Pengantar:</span>
                        <span class="font-bold text-xs text-gray-800 block">
                            {{ $ord->driver ? $ord->driver->name : 'Tim Supir CROWN & LUCKY' }}
                        </span>
                        <span class="text-[11px] text-emerald-600 font-semibold block mt-0.5">Diterima di Alamat</span>
                    </div>

                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Lama Pengerjaan:</span>
                        <span class="text-xs font-semibold text-gray-700 block">
                            {{ $ord->picked_up_at && $ord->delivered_at ? $ord->picked_up_at->diffInHours($ord->delivered_at) . ' Jam' : ($ord->estimated_hours . ' Jam') }}
                        </span>
                        @if($ord->delay_minutes > 0)
                            <span class="text-[10px] text-rose-500 block">Ada perpanjangan waktu noda</span>
                        @endif
                    </div>

                    <div class="sm:text-right">
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Total Pembayaran:</span>
                        <span class="text-base font-bold font-serif text-primary">
                            Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-emerald-600 font-bold block uppercase">Lunas (Paid)</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-border/80 text-xs">
                    <span class="text-gray-400 text-[11px]">Alamat Pengantaran: {{ $ord->customer_address }}</span>
                    <button @click="receiptModal = true; selectedReceipt = {{ json_encode($ord) }}" class="text-primary hover:underline font-bold flex items-center gap-1">
                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                        <span>Lihat Rincian Resi</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-border shadow-sm text-gray-400 text-xs space-y-2">
                <i data-lucide="inbox" class="w-10 h-10 mx-auto text-gray-300"></i>
                <p>Belum ada riwayat laundry yang telah selesai diantar.</p>
                <a href="{{ route('customer.order.create') }}" class="text-primary font-bold hover:underline inline-block mt-2">
                    Mulai Pesan Laundry Sekarang &rarr;
                </a>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Modal Resi / Invoice -->
    <div x-show="receiptModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.away="receiptModal = false" class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full space-y-6 shadow-2xl border border-border" id="printable-receipt">
            <div class="text-center pb-4 border-b border-border space-y-1">
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white mx-auto mb-2 shadow">
                    <i data-lucide="waves" class="w-5 h-5"></i>
                </div>
                <h3 class="font-serif font-bold text-lg text-foreground">CROWN & LUCKY LAUNDRY</h3>
                <p class="text-[11px] text-gray-500">Bukti Pembayaran & Pengantaran Selesai</p>
                <div class="font-mono text-xs font-bold text-primary mt-1" x-text="selectedReceipt.order_code"></div>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-dashed border-border">
                    <span class="text-gray-500">Nama Pelanggan:</span>
                    <strong class="text-foreground" x-text="selectedReceipt.customer_name"></strong>
                </div>
                <div class="flex justify-between py-1 border-b border-dashed border-border">
                    <span class="text-gray-500">Paket Layanan:</span>
                    <strong class="text-foreground" x-text="selectedReceipt.package_name"></strong>
                </div>
                <div class="flex justify-between py-1 border-b border-dashed border-border">
                    <span class="text-gray-500">Total Potong Pakaian:</span>
                    <strong class="text-foreground" x-text="(selectedReceipt.driver_item_count || selectedReceipt.operator_item_count) + ' Potong'"></strong>
                </div>
                <div class="flex justify-between py-1 border-b border-dashed border-border">
                    <span class="text-gray-500">Berat Cucian:</span>
                    <strong class="text-foreground" x-text="selectedReceipt.weight + ' Kg'"></strong>
                </div>
                <div class="flex justify-between py-1 border-b border-dashed border-border">
                    <span class="text-gray-500">Tarif per Kg:</span>
                    <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedReceipt.price_per_kg || 0)"></span>
                </div>
                <div class="flex justify-between py-2 border-t-2 border-border text-sm font-bold">
                    <span class="text-foreground">Total Tagihan:</span>
                    <span class="text-primary font-serif font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedReceipt.total_price || 0)"></span>
                </div>
                <div class="text-center pt-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        STATUS: LUNAS & TELAH DITERIMA
                    </span>
                </div>
            </div>

            <div class="flex gap-2 pt-2 border-t border-border">
                <button type="button" @click="receiptModal = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold">
                    Tutup
                </button>
                <button type="button" onclick="window.print()" class="flex-1 py-2.5 bg-primary text-white rounded-xl text-xs font-bold shadow hover:bg-primary-hover flex items-center justify-center gap-1.5">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Cetak Resi</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

