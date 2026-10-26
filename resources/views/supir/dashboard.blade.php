@extends('layouts.dashboard')

@section('title', 'Dashboard Operasional Supir (Driver)')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                🚚 Akun Supir (Armada Antar - Jemput)
            </div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold">Halo, {{ Auth::user()->name }}</h1>
            <p class="text-white/80 text-xs md:text-sm mt-1 max-w-xl">
                Fitur utama Supir: <strong>Input jumlah total cucian</strong> saat serah terima pakaian dari pelanggan. Jumlah yang diinput supir langsung tersimpan dan otomatis tampil secara live di akun <strong>Owner, Admin, Operator, dan Customer</strong>.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-3 text-center">
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/20">
                <span class="text-2xl font-bold font-serif">{{ $stats['needs_pickup_count'] }}</span>
                <span class="text-[10px] block text-white/80 uppercase tracking-wider font-semibold">Tugas Input & Jemput</span>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/20">
                <span class="text-2xl font-bold font-serif">{{ $stats['ready_to_deliver_count'] }}</span>
                <span class="text-[10px] block text-white/80 uppercase tracking-wider font-semibold">Siap Diantar</span>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Shortcuts -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <a href="#pickup-section" class="bg-white p-3.5 rounded-2xl border-2 border-primary/40 hover:border-primary shadow-sm flex items-center gap-3 transition-all">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                <i data-lucide="clipboard-edit" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Fitur 1</span>
                <span class="text-xs font-bold text-foreground">Input Jumlah Cucian ({{ $pickupQueue->count() }})</span>
            </div>
        </a>
        <a href="#drop-section" class="bg-white p-3.5 rounded-2xl border border-border hover:border-indigo-400 shadow-sm flex items-center gap-3 transition-all">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <i data-lucide="arrow-down-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Fitur 2</span>
                <span class="text-xs font-bold text-foreground">Drop di Laundry ({{ $dropQueue->count() }})</span>
            </div>
        </a>
        <a href="#delivery-section" class="bg-white p-3.5 rounded-2xl border border-border hover:border-emerald-400 shadow-sm flex items-center gap-3 transition-all">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <i data-lucide="truck" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Fitur 3</span>
                <span class="text-xs font-bold text-foreground">Antrian Pengantaran ({{ $deliveryQueue->count() }})</span>
            </div>
        </a>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. BAGIAN UTAMA SUPIR: INPUT JUMLAH TOTAL CUCIAN & PENJEMPUTAN BARANG -->
    <!-- ========================================================================= -->
    <div id="pickup-section" class="bg-white rounded-3xl p-6 md:p-8 border-2 border-primary ring-4 ring-primary/10 shadow-lg space-y-6" x-data="{ showNewOrderForm: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-border gap-3">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-1">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Fitur Utama Supir Sesuai Ketentuan</span>
                </div>
                <h2 class="text-xl font-serif font-bold text-foreground flex items-center gap-2">
                    <i data-lucide="clipboard-pen" class="w-6 h-6 text-primary"></i>
                    <span>Form Input Jumlah Total Cucian yang Diambil</span>
                </h2>
                <p class="text-xs text-gray-600 mt-1 max-w-2xl">
                    Hitung dan masukkan <strong>jumlah potong pakaian</strong> saat serah terima di pelanggan. Hasil inputan Anda akan tampil langsung di akun <strong>Owner, Admin, Operator</strong> (sebagai acuan hitung ulang selisih), dan <strong>Customer</strong>.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="showNewOrderForm = !showNewOrderForm" class="px-4 py-2.5 bg-secondary hover:bg-secondary/80 text-secondary-foreground rounded-xl text-xs font-bold flex items-center gap-1.5 border border-border shadow-sm transition-all">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span x-text="showNewOrderForm ? 'Tutup Form Tambah' : '+ Input Cucian Baru di Lokasi'"></span>
                </button>
                <span class="text-xs font-bold bg-primary text-white px-3.5 py-2 rounded-xl shadow-sm">
                    {{ $pickupQueue->count() }} Menunggu Diinput
                </span>
            </div>
        </div>

        <!-- FORM TAMBAHAN: INPUT PESANAN BARU LANGSUNG DI TEMPAT (JIKA PELANGGAN SERAH TERIMA TANPA PESAN ONLINE DULU) -->
        <div x-show="showNewOrderForm" x-transition class="bg-blue-50/70 p-6 rounded-2xl border-2 border-blue-300 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-blue-950 flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-4 h-4 text-blue-600"></i>
                        <span>Input Cucian Langsung di Lokasi Pelanggan (Pesanan Baru)</span>
                    </h3>
                    <p class="text-xs text-blue-800/80">Gunakan form ini jika pelanggan menyerahkan pakaian langsung ke supir di lokasi tanpa membuat pesanan di web sebelumnya.</p>
                </div>
                <button @click="showNewOrderForm = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('supir.create-pickup') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Pelanggan *</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Ibu Rina" class="w-full px-3.5 py-2.5 rounded-xl border border-border bg-white text-xs outline-none focus:ring-2 focus:ring-primary font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nomor Telepon / WA *</label>
                        <input type="tel" name="customer_phone" required placeholder="081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-border bg-white text-xs outline-none focus:ring-2 focus:ring-primary font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Pilihan Paket *</label>
                        <select name="package_code" required class="w-full px-3.5 py-2.5 rounded-xl border border-border bg-white text-xs outline-none focus:ring-2 focus:ring-primary font-semibold">
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->code }}">{{ $pkg->name }} (Rp {{ number_format($pkg->price_per_kg, 0, ',', '.') }}/kg)</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alamat Penjemputan / Pengantaran *</label>
                    <input type="text" name="customer_address" required placeholder="Contoh: Jl. Kemang Raya No. 12, Jakarta Selatan" class="w-full px-3.5 py-2.5 rounded-xl border border-border bg-white text-xs outline-none focus:ring-2 focus:ring-primary">
                </div>

                <div class="grid md:grid-cols-3 gap-4 p-4 rounded-xl bg-white border border-blue-200">
                    <div>
                        <label class="block text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">
                            JUMLAH TOTAL PAKAIAN (POTONG) *
                        </label>
                        <input type="number" name="driver_item_count" min="1" required placeholder="Contoh: 18" class="w-full px-3.5 py-2.5 rounded-xl border-2 border-emerald-500 bg-emerald-50/30 text-base font-bold text-emerald-900 outline-none focus:ring-2 focus:ring-emerald-600">
                        <span class="text-[10px] text-gray-500 mt-1 block">Wajib dihitung saat terima barang</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Berat Timbangan (Kg)</label>
                        <input type="number" name="weight" step="0.1" min="0.5" value="4.0" class="w-full px-3.5 py-2.5 rounded-xl border border-border bg-white text-sm font-semibold outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Catatan Tambahan Supir</label>
                        <input type="text" name="driver_notes" placeholder="Misal: plastik merah, ada selimut" class="w-full px-3.5 py-2.5 rounded-xl border border-border bg-white text-xs outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showNewOrderForm = false" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition-all">
                        Simpan Pesanan & Ambil Langsung
                    </button>
                </div>
            </form>
        </div>

        <!-- DAFTAR PESANAN YANG MENUNGGU INPUT & JEMPUT -->
        @if($pickupQueue->count() > 0)
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($pickupQueue as $ord)
                    <div class="bg-background rounded-3xl p-6 border-2 border-primary/30 shadow-sm space-y-4 hover:border-primary transition-all">
                        <!-- Header Card -->
                        <div class="flex items-center justify-between pb-3 border-b border-border">
                            <div>
                                <span class="font-mono font-bold text-sm text-primary">{{ $ord->order_code }}</span>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $ord->priority_badge_class }} ml-1">
                                    {{ $ord->package_name }}
                                </span>
                            </div>
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Input Supir
                            </span>
                        </div>

                        <!-- Customer Info -->
                        <div class="space-y-1.5 text-xs">
                            <div class="font-bold text-sm text-foreground flex items-center gap-1.5">
                                <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                                <span>{{ $ord->customer_name }}</span>
                            </div>
                            <div class="text-gray-600 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                                <span>{{ $ord->customer_phone }}</span>
                            </div>
                            <div class="text-gray-600 flex items-start gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5"></i>
                                <span>{{ $ord->customer_address }}</span>
                            </div>
                            @if($ord->customer_notes)
                                <div class="text-[11px] text-amber-900 bg-amber-50 p-2.5 rounded-xl border border-amber-200">
                                    <strong>Catatan Pelanggan:</strong> {{ $ord->customer_notes }}
                                </div>
                            @endif
                        </div>

                        <!-- ==================================================== -->
                        <!-- FORM INPUT JUMLAH TOTAL CUCIAN (LANGSUNG TAMPIL INLINE) -->
                        <!-- ==================================================== -->
                        <form action="{{ route('supir.pickup', $ord->id) }}" method="POST" class="bg-white p-4 rounded-2xl border-2 border-emerald-300 space-y-3.5 shadow-sm">
                            @csrf
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                    <i data-lucide="edit-3" class="w-4 h-4 text-emerald-600"></i>
                                    <span>Input Jumlah Cucian Serah Terima:</span>
                                </span>
                                <span class="text-[10px] text-gray-500 font-medium">Wajib diisi</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-wider mb-1">
                                        Total Pakaian (Pcs) *
                                    </label>
                                    <input type="number" name="driver_item_count" min="1" required placeholder="Contoh: 15" class="w-full px-3.5 py-2.5 rounded-xl border-2 border-emerald-500 bg-emerald-50/20 text-base font-bold text-emerald-950 focus:ring-2 focus:ring-emerald-600 outline-none">
                                    <span class="text-[9px] text-gray-500 mt-0.5 block">Hitung per potong</span>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-wider mb-1">
                                        Berat Timbangan (Kg)
                                    </label>
                                    <input type="number" name="weight" step="0.1" min="0.5" value="{{ $ord->weight ?: 4.0 }}" class="w-full px-3.5 py-2.5 rounded-xl border border-border bg-gray-50 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none">
                                    <span class="text-[9px] text-gray-500 mt-0.5 block">Estimasi/riil timbangan</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">
                                    Catatan Supir Saat Jemput (Opsional)
                                </label>
                                <input type="text" name="driver_notes" placeholder="Misal: kantong plastik biru, ada jas hitam" class="w-full px-3 py-2 rounded-xl border border-border text-xs focus:ring-2 focus:ring-primary outline-none">
                            </div>

                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                <span>Simpan &amp; Tandai Telah Diambil</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-emerald-50/50 rounded-2xl border-2 border-dashed border-emerald-200 text-gray-600 text-xs space-y-3">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div class="font-bold text-sm text-foreground">Semua Cucian yang Dijadwalkan Telah Diambil & Diinput!</div>
                <p class="max-w-md mx-auto text-gray-500">
                    Tidak ada pesanan online yang sedang menunggu penjemputan. Jika Anda menerima pakaian langsung dari pelanggan baru di lokasi, klik tombol <strong>"+ Input Cucian Baru di Lokasi"</strong> di atas.
                </p>
                <button @click="showNewOrderForm = true" class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl text-xs font-bold transition-colors shadow">
                    + Input Cucian Baru Sekarang
                </button>
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- 2. ANTRIAN DROP DI WORKSHOP LAUNDRY -->
    <!-- ========================================================================= -->
    <div id="drop-section" class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-border">
            <div>
                <h2 class="text-lg font-serif font-bold text-foreground flex items-center gap-2">
                    <i data-lucide="arrow-down-circle" class="w-5 h-5 text-indigo-600"></i>
                    <span>Pakaian Dalam Mobil (Siap di-Drop ke Tempat Laundry)</span>
                </h2>
                <p class="text-xs text-gray-500">Setelah sampai di laundry, ubah status menjadi <strong>"Telah Drop"</strong> agar antrian otomatis muncul sebagai <strong>Siap Dikerjakan</strong> pada akun Operator.</p>
            </div>
            <span class="text-xs font-bold bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full">
                {{ $dropQueue->count() }} Pakaian di Mobil
            </span>
        </div>

        @if($dropQueue->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($dropQueue as $dropItem)
                    <div class="bg-indigo-50/50 rounded-2xl p-5 border border-indigo-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-xs text-primary">{{ $dropItem->order_code }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $dropItem->priority_badge_class }}">
                                {{ $dropItem->package_name }}
                            </span>
                        </div>

                        <div>
                            <div class="font-bold text-xs text-foreground">{{ $dropItem->customer_name }}</div>
                            <div class="text-xs font-bold text-indigo-900 mt-1 bg-white p-2 rounded-lg border border-indigo-100">
                                📦 Input Supir: <strong>{{ $dropItem->driver_item_count }} Potong</strong> ({{ $dropItem->weight }} kg)
                            </div>
                            <div class="text-[10px] text-gray-500 mt-1">
                                Diambil pada {{ $dropItem->picked_up_at ? $dropItem->picked_up_at->translatedFormat('H:i') : '-' }} WIB
                            </div>
                        </div>

                        <form action="{{ route('supir.drop', $dropItem->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors shadow flex items-center justify-center gap-1.5">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Telah Drop di Laundry (Siap Dikerjakan)</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-2xl border border-dashed border-border text-gray-400 text-xs">
                Tidak ada pakaian yang sedang dibawa di mobil. Ambil pakaian pada bagian atas terlebih dahulu.
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- 3. NOTIFIKASI & ANTRIAN PENGANTARAN (SIAP DIANTAR KE PELANGGAN) -->
    <!-- ========================================================================= -->
    <div id="delivery-section" class="bg-white rounded-3xl p-6 md:p-8 border-2 {{ $deliveryQueue->count() > 0 ? 'border-emerald-500 shadow-xl ring-4 ring-emerald-500/10' : 'border-border shadow-sm' }} space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-border gap-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl {{ $deliveryQueue->count() > 0 ? 'bg-emerald-500 text-white animate-bounce' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center shadow-md">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-serif font-bold text-foreground">Antrian Pengantaran (Siap Diantar ke Pelanggan)</h2>
                        @if($deliveryQueue->count() > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 animate-pulse">
                                {{ $deliveryQueue->count() }} Pakaian Selesai!
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">Muncul otomatis saat operator laundry mengubah status menjadi "Selesai / Siap Diantar".</p>
                </div>
            </div>
        </div>

        @if($deliveryQueue->count() > 0)
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($deliveryQueue as $item)
                    <div class="bg-emerald-50/50 rounded-2xl p-5 border border-emerald-200 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono font-bold text-sm text-primary">{{ $item->order_code }}</span>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item->priority_badge_class }} ml-1">
                                    {{ $item->package_name }}
                                </span>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $item->status_badge_class }}">
                                {{ $item->status_label }}
                            </span>
                        </div>

                        <div class="space-y-1.5 text-xs text-gray-700">
                            <div class="font-bold text-sm text-foreground flex items-center gap-1.5">
                                <i data-lucide="user" class="w-4 h-4 text-emerald-600"></i>
                                <span>{{ $item->customer_name }} ({{ $item->customer_phone }})</span>
                            </div>
                            <div class="flex items-start gap-1.5 text-gray-600">
                                <i data-lucide="map-pin" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                                <span>{{ $item->customer_address }}</span>
                            </div>
                            <div class="bg-white p-2.5 rounded-xl border border-emerald-100 flex justify-between text-xs font-semibold">
                                <span>Total Pakaian: <strong>{{ $item->driver_item_count ?? $item->operator_item_count }} Potong</strong></span>
                                <span>Total Tagihan: <strong class="text-primary font-bold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-2 flex gap-2">
                            @if($item->status === 'siap_diantar')
                                <form action="{{ route('supir.start-delivery', $item->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1.5 shadow">
                                        <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                                        <span>Mulai Pengantaran</span>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('supir.deliver', $item->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Konfirmasi: Pakaian telah sampai di tangan pelanggan?')">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1.5 shadow">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                    <span>Telah Diantar (Selesai)</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-border text-gray-400 text-xs space-y-2">
                <i data-lucide="package-check" class="w-8 h-8 mx-auto text-gray-300"></i>
                <p>Belum ada cucian yang siap diantar. Tunggu hingga operator menyelesaikan proses cuci.</p>
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- 4. RIWAYAT PENGANTARAN HARI INI -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm space-y-4">
        <h2 class="text-base font-serif font-bold text-foreground">Riwayat Pengantaran Selesai</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-[10px] font-bold uppercase text-gray-500">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Jumlah Potong</th>
                        <th class="p-3">Waktu Diantar</th>
                        <th class="p-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($completedOrders as $co)
                        <tr>
                            <td class="p-3 font-mono font-bold text-primary">{{ $co->order_code }}</td>
                            <td class="p-3 font-semibold">{{ $co->customer_name }}</td>
                            <td class="p-3 font-bold">{{ $co->driver_item_count }} pcs</td>
                            <td class="p-3 text-gray-500">{{ $co->delivered_at ? $co->delivered_at->translatedFormat('d M, H:i') : '-' }}</td>
                            <td class="p-3 text-right">
                                <span class="text-emerald-700 font-bold bg-emerald-100 px-2 py-0.5 rounded-full text-[10px]">
                                    Telah Diantar
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-400">Belum ada riwayat pengantaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
