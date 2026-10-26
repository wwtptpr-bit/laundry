@extends('layouts.dashboard')

@section('title', 'Dashboard Operator Laundry & Antrian Prioritas')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto" x-data="{ recountModal: false, delayModal: false, activeOrder: {} }">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-700 to-indigo-800 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                ⚙️ Akun Operator (Pengerjaan & Quality Control)
            </div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold">Stasiun Kerja Operator</h1>
            <p class="text-white/80 text-xs md:text-sm mt-1 max-w-2xl leading-relaxed">
                Antrian laundry otomatis didahulukan berdasarkan paket: <span class="font-bold underline text-rose-300">Prioritas</span> &gt; <span class="font-bold underline text-purple-200">VVIP</span> &gt; <span class="font-bold underline text-amber-200">VIP</span> &gt; <span class="font-bold underline text-emerald-200">Reguler</span>.
                Gunakan fitur <strong>Hitung Ulang</strong> untuk mendeteksi selisih barang, <strong>Penundaan</strong> untuk kaji ulang estimasi, dan <strong>Proses Selesai</strong> untuk meneruskan info ke supir.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-3 text-center">
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/20">
                <span class="text-2xl font-bold font-serif">{{ $stats['ready_to_work'] }}</span>
                <span class="text-[10px] block text-white/80 uppercase tracking-wider font-semibold">Siap Dikerjakan</span>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/20">
                <span class="text-2xl font-bold font-serif">{{ $stats['in_progress'] }}</span>
                <span class="text-[10px] block text-white/80 uppercase tracking-wider font-semibold">Sedang Diproses</span>
            </div>
        </div>
    </div>

    <!-- Priority Queue Rules Notice -->
    <div class="bg-gradient-to-r from-amber-500/10 via-purple-500/10 to-primary/10 rounded-2xl p-4 border border-border flex items-center justify-between text-xs text-foreground">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center shrink-0">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <div>
                <span class="font-bold">Aturan Prioritas Antrean:</span>
                <span class="text-gray-600">Pelanggan dengan paket VIP diprioritaskan di atas Reguler, VVIP di atas VIP, dan Prioritas dikerjakan paling awal!</span>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-1.5 text-[11px] font-bold">
            <span class="bg-rose-500 text-white px-2 py-0.5 rounded">1. Prioritas</span>
            <span>&gt;</span>
            <span class="bg-purple-600 text-white px-2 py-0.5 rounded">2. VVIP</span>
            <span>&gt;</span>
            <span class="bg-amber-500 text-white px-2 py-0.5 rounded">3. VIP</span>
            <span>&gt;</span>
            <span class="bg-emerald-600 text-white px-2 py-0.5 rounded">4. Reguler</span>
        </div>
    </div>

    <!-- MAIN PRIORITY QUEUE -->
    <div id="queue-section" class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-border gap-2">
            <div>
                <h2 class="text-lg font-serif font-bold text-foreground flex items-center gap-2">
                    <i data-lucide="sliders" class="w-5 h-5 text-purple-600"></i>
                    <span>Antrian Pengerjaan Laundry Berdasarkan Prioritas Layanan</span>
                </h2>
                <p class="text-xs text-gray-500">Urutan teratas adalah cucian yang wajib dikerjakan terlebih dahulu.</p>
            </div>
            <span class="text-xs font-bold bg-purple-100 text-purple-900 px-3 py-1 rounded-full">
                {{ $queueOrders->count() }} Cucian dalam Antrean
            </span>
        </div>

        <div class="space-y-4">
            @forelse($queueOrders as $idx => $ord)
                <div class="rounded-2xl p-5 border-2 transition-all 
                    {{ $ord->package_code === 'prioritas' ? 'border-rose-300 bg-rose-50/20 shadow-md' : ($ord->package_code === 'vvip' ? 'border-purple-200 bg-purple-50/20' : ($ord->package_code === 'vip' ? 'border-amber-200 bg-amber-50/20' : 'border-border bg-background')) }}">
                    
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-border/80">
                        <div class="flex items-start gap-3">
                            <!-- Priority Rank Badge -->
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-sm
                                {{ $ord->package_code === 'prioritas' ? 'bg-gradient-to-br from-rose-500 to-red-600 text-white animate-pulse' : ($ord->package_code === 'vvip' ? 'bg-purple-600 text-white' : ($ord->package_code === 'vip' ? 'bg-amber-500 text-white' : 'bg-emerald-600 text-white')) }}">
                                #{{ $idx + 1 }}
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-mono font-bold text-sm text-primary">{{ $ord->order_code }}</span>
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $ord->priority_badge_class }}">
                                        {{ $ord->package_name }} (Level {{ $ord->priority_level }}/4)
                                    </span>
                                    <span class="text-xs font-bold px-3 py-0.5 rounded-full border {{ $ord->status_badge_class }}">
                                        {{ $ord->status_label }}
                                    </span>
                                </div>
                                <div class="font-bold text-sm text-foreground mt-1">{{ $ord->customer_name }} &bull; {{ $ord->customer_phone }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $ord->customer_address }}</div>
                            </div>
                        </div>

                        <!-- Time & Countdown -->
                        <div class="lg:text-right text-xs">
                            <span class="text-gray-500 block">Estimasi Selesai:</span>
                            <span class="font-bold text-sm text-foreground block">
                                {{ $ord->estimated_completion_at ? $ord->estimated_completion_at->translatedFormat('d M, H:i') : '-' }} WIB
                            </span>
                            @if($ord->status === 'pending')
                                <span class="text-[11px] font-bold text-rose-600 block mt-0.5">
                                    ⚠️ Penundaan: +{{ $ord->delay_minutes }} mnt
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Transparency & Recount Status Bar -->
                    <div class="py-3 grid sm:grid-cols-3 gap-3 text-xs">
                        <div class="bg-white p-3 rounded-xl border border-border">
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Input Supir Saat Jemput:</span>
                            <span class="text-sm font-bold text-foreground">
                                {{ $ord->driver_item_count ? $ord->driver_item_count . ' Potong' : 'Belum dihitung supir' }}
                            </span>
                            <span class="text-[11px] text-gray-500 block">Berat: {{ $ord->weight }} kg</span>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-border">
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Hitung Ulang Operator:</span>
                            <span class="text-sm font-bold text-foreground">
                                {{ $ord->operator_item_count ? $ord->operator_item_count . ' Potong' : 'Belum dihitung ulang' }}
                            </span>
                        </div>

                        <div class="p-3 rounded-xl border {{ $ord->hasDiscrepancy() ? 'bg-rose-100/60 border-rose-300 text-rose-900' : 'bg-white border-border text-gray-700' }}">
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Deteksi Selisih Barang:</span>
                            @if($ord->operator_item_count === null)
                                <span class="text-xs text-gray-400 italic">Perlu hitung ulang</span>
                            @elseif($ord->item_discrepancy === 0)
                                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Cocok (0 Selisih)
                                </span>
                            @else
                                <span class="text-xs font-bold text-rose-700 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                    Selisih: {{ $ord->item_discrepancy > 0 ? '+'.$ord->item_discrepancy : $ord->item_discrepancy }} Potong!
                                </span>
                                <div class="text-[10px] text-rose-600 mt-0.5 font-medium leading-tight">
                                    {{ $ord->discrepancy_note }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($ord->status === 'pending')
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-xs text-rose-800 mb-3">
                            <strong>Alasan Penundaan:</strong> {{ $ord->delay_reason }}
                        </div>
                    @endif

                    <!-- OPERATOR ACTION BUTTONS -->
                    <div class="pt-3 border-t border-border flex flex-wrap gap-2 items-center">
                        <!-- 1. Hitung Ulang Button (Tidak mengubah input supir) -->
                        <button @click="recountModal = true; activeOrder = {{ json_encode($ord) }}" class="px-3.5 py-2 bg-white hover:bg-gray-50 border border-border text-gray-700 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm">
                            <i data-lucide="calculator" class="w-3.5 h-3.5 text-purple-600"></i>
                            <span>{{ $ord->operator_item_count !== null ? 'Koreksi Hitung Ulang' : 'Input Hitung Ulang' }}</span>
                        </button>

                        <!-- 2. Status Progression Buttons -->
                        @if($ord->status === 'siap_dikerjakan')
                            <form action="{{ route('operator.start', $ord->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-xs font-bold transition-colors shadow flex items-center gap-1.5">
                                    <i data-lucide="play" class="w-3.5 h-3.5"></i>
                                    <span>Mulai Pengerjaan (Proses)</span>
                                </button>
                            </form>
                        @elseif($ord->status === 'proses')
                            <!-- Penundaan Button -->
                            <button @click="delayModal = true; activeOrder = {{ json_encode($ord) }}" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 rounded-xl text-xs font-bold flex items-center gap-1.5">
                                <i data-lucide="pause-circle" class="w-3.5 h-3.5 text-amber-600"></i>
                                <span>Tunda (Pending)</span>
                            </button>

                            <!-- Selesai Button -> notifies driver for delivery -->
                            <form action="{{ route('operator.finish', $ord->id) }}" method="POST" onsubmit="return confirm('Konfirmasi: Cucian sudah selesai dicuci, disetrika, dan siap diantar oleh supir?')">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:opacity-95 text-white rounded-xl text-xs font-bold transition-all shadow flex items-center gap-1.5">
                                    <i data-lucide="check-check" class="w-3.5 h-3.5"></i>
                                    <span>Proses Selesai & Kirim Notif ke Supir</span>
                                </button>
                            </form>
                        @elseif($ord->status === 'pending')
                            <!-- Resume Button -->
                            <form action="{{ route('operator.resume', $ord->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-colors shadow flex items-center gap-1.5">
                                    <i data-lucide="play-circle" class="w-3.5 h-3.5"></i>
                                    <span>Lanjutkan Pengerjaan</span>
                                </button>
                            </form>
                        @elseif($ord->status === 'telah_diambil')
                            <span class="text-xs text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-xl font-medium flex items-center gap-1">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                <span>Sedang dibawa supir menuju workshop (Telah Diambil)</span>
                            </span>
                        @endif
                    </div>

                </div>
            @empty
                <div class="text-center py-12 text-gray-400 text-xs">
                    Semua antrean cucian telah selesai dikerjakan!
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL 1: HITUNG ULANG BARANG (RECOUNT) -->
    <div x-show="recountModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.away="recountModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 shadow-2xl border border-border">
            <div class="flex items-center justify-between pb-3 border-b border-border">
                <div>
                    <h3 class="font-bold text-sm text-foreground">Hitung Ulang Jumlah Pakaian (QC)</h3>
                    <span class="text-xs font-mono text-primary font-bold" x-text="activeOrder.order_code"></span>
                </div>
                <button @click="recountModal = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="bg-secondary/60 p-3 rounded-xl border border-border text-xs">
                <span class="text-gray-500 block">Inputan Supir Saat Ambil:</span>
                <span class="text-base font-bold text-foreground" x-text="(activeOrder.driver_item_count || 0) + ' Potong Pakaian'"></span>
                <span class="text-[10px] text-gray-500 block mt-0.5">Input supir tidak akan diubah/ditimpa. Sistem akan membandingkan untuk mendeteksi selisih.</span>
            </div>

            <form :action="'/operator/order/' + activeOrder.id + '/recount'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Jumlah Pakaian Hasil Hitungan Operator *
                    </label>
                    <input type="number" name="operator_item_count" :value="activeOrder.operator_item_count ?? activeOrder.driver_item_count" min="0" required class="w-full px-4 py-3 rounded-xl border border-border text-base font-bold text-purple-700 focus:ring-2 focus:ring-purple-600 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Catatan Selisih / Kondisi Pakaian (Opsional)
                    </label>
                    <textarea name="discrepancy_note" rows="2" placeholder="Jelaskan bila ada noda luntur, kancing lepas, atau selisih potong..." class="w-full px-4 py-2 rounded-xl border border-border text-xs focus:ring-2 focus:ring-purple-600 outline-none" x-text="activeOrder.discrepancy_note || ''"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="recountModal = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow">
                        Simpan Hasil Hitung
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: PENUNDAAN PROSES (DELAY / POSTPONE) -->
    <div x-show="delayModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div @click.away="delayModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 shadow-2xl border border-border">
            <div class="flex items-center justify-between pb-3 border-b border-border">
                <div>
                    <h3 class="font-bold text-sm text-foreground">Penundaan Proses Laundry (Pending)</h3>
                    <span class="text-xs font-mono text-primary font-bold" x-text="activeOrder.order_code"></span>
                </div>
                <button @click="delayModal = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="bg-amber-50 p-3 rounded-xl border border-amber-200 text-xs text-amber-900 leading-relaxed">
                Melakukan penundaan akan mengubah status menjadi <strong>Pending</strong>, serta <strong>mengkaji ulang estimasi waktu selesai</strong> dan meneruskan info ini ke Owner, Admin, dan Customer.
            </div>

            <form :action="'/operator/order/' + activeOrder.id + '/delay'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Alasan Penundaan *
                    </label>
                    <textarea name="delay_reason" rows="3" required placeholder="Contoh: Perendaman khusus noda membandel / pemadaman listrik berkala / bahan sensitif butuh pengerjaan manual" class="w-full px-4 py-2.5 rounded-xl border border-border text-xs focus:ring-2 focus:ring-amber-500 outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Durasi Penundaan (Jam) *
                    </label>
                    <input type="number" name="delay_hours" step="0.5" min="0.5" max="48" value="2" required class="w-full px-4 py-2.5 rounded-xl border border-border text-sm font-bold text-amber-800 focus:ring-2 focus:ring-amber-500 outline-none">
                    <span class="text-[10px] text-gray-500 mt-1 block">Waktu estimasi selesai di dashboard customer akan otomatis diundur sesuai durasi ini.</span>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="delayModal = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow">
                        Konfirmasi Penundaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

