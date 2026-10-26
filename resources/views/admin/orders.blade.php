@extends('layouts.dashboard')

@section('title', 'Daftar Pesanan (Admin Read-Only)')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-900 text-xs font-bold uppercase tracking-wider mb-2">
                🛡️ Mode Admin: Hanya Pantau
            </div>
            <h1 class="text-2xl font-serif font-bold text-foreground">Pemantauan Pesanan & Transaksi</h1>
            <p class="text-xs text-gray-500 mt-1">Lihat status pesanan, inputan supir, hitung ulang operator, dan deteksi selisih secara transparan.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Akses: Read-Only</span>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl p-4 border border-border shadow-sm">
        <form method="GET" action="{{ route('admin.orders') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama / telepon..." class="w-full px-3 py-2 rounded-xl border border-border text-xs focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-border text-xs focus:ring-2 focus:ring-primary outline-none bg-white">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_penjemputan" {{ request('status') === 'menunggu_penjemputan' ? 'selected' : '' }}>Menunggu Penjemputan</option>
                    <option value="telah_diambil" {{ request('status') === 'telah_diambil' ? 'selected' : '' }}>Telah Diambil Supir</option>
                    <option value="siap_dikerjakan" {{ request('status') === 'siap_dikerjakan' ? 'selected' : '' }}>Drop / Siap Dikerjakan</option>
                    <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                    <option value="siap_diantar" {{ request('status') === 'siap_diantar' ? 'selected' : '' }}>Selesai / Siap Diantar</option>
                    <option value="telah_diantar" {{ request('status') === 'telah_diantar' ? 'selected' : '' }}>Telah Diantar (Selesai)</option>
                </select>
            </div>
            <div>
                <select name="discrepancy" class="w-full px-3 py-2 rounded-xl border border-border text-xs focus:ring-2 focus:ring-primary outline-none bg-white">
                    <option value="">-- Status Selisih Barang --</option>
                    <option value="yes" {{ request('discrepancy') === 'yes' ? 'selected' : '' }}>⚠️ Hanya yang Ada Selisih</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-hover transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.orders') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-border text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="p-4">Kode & Pelanggan</th>
                        <th class="p-4">Paket & Prioritas</th>
                        <th class="p-4">Status Alur</th>
                        <th class="p-4">Input Supir</th>
                        <th class="p-4">Hitung Operator</th>
                        <th class="p-4">Estimasi Selesai</th>
                        <th class="p-4 text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-gray-50/80 transition-colors {{ $ord->hasDiscrepancy() ? 'bg-rose-50/30' : '' }}">
                            <td class="p-4">
                                <span class="font-mono font-bold text-primary block">{{ $ord->order_code }}</span>
                                <div class="font-bold text-gray-800 mt-0.5">{{ $ord->customer_name }}</div>
                                <div class="text-[11px] text-gray-400">{{ $ord->customer_phone }}</div>
                            </td>
                            <td class="p-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ord->priority_badge_class }}">
                                    {{ $ord->package_name }}
                                </span>
                                <div class="text-[10px] text-gray-400 mt-0.5">Level {{ $ord->priority_level }}/4</div>
                            </td>
                            <td class="p-4">
                                <span class="inline-block px-2.5 py-1 rounded-full border text-[10px] font-bold {{ $ord->status_badge_class }}">
                                    {{ $ord->status_label }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-800">
                                    {{ $ord->driver_item_count ? $ord->driver_item_count . ' Pcs' : '-' }}
                                </div>
                                <div class="text-[10px] text-gray-400">Berat: {{ $ord->weight }} kg</div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-800">
                                    {{ $ord->operator_item_count ? $ord->operator_item_count . ' Pcs' : '-' }}
                                </div>
                                @if($ord->hasDiscrepancy())
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 mt-0.5">
                                        Selisih: {{ $ord->item_discrepancy > 0 ? '+'.$ord->item_discrepancy : $ord->item_discrepancy }} pcs
                                    </span>
                                @elseif($ord->operator_item_count !== null)
                                    <span class="text-[10px] text-emerald-600 font-bold">Cocok</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="text-gray-700 font-medium">
                                    {{ $ord->estimated_completion_at ? $ord->estimated_completion_at->translatedFormat('d M, H:i') : '-' }}
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <div class="font-bold font-serif text-sm text-foreground">
                                    Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                                </div>
                                <span class="text-[10px] font-bold uppercase {{ $ord->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $ord->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400">
                                Tidak ada data pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

