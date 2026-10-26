@extends('layouts.dashboard')

@section('title', 'Pesan Laundry Baru - Pilihan Paket Layanan')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto" x-data="{
    selectedPackage: '{{ $packages->first()->code ?? 'reguler' }}',
    selectedPrice: {{ $packages->first()->price_per_kg ?? 6000 }},
    estimatedHours: {{ $packages->first()->estimated_hours ?? 48 }},
    priorityLevel: {{ $packages->first()->priority_level ?? 1 }},
    packageName: '{{ $packages->first()->name ?? 'Paket Reguler' }}',
    weight: 5,
    get totalPrice() {
        return this.selectedPrice * this.weight;
    }
}">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-secondary-foreground text-xs font-bold uppercase tracking-wider mb-2">
            PILIHAN 4 PAKET LAYANAN
        </div>
        <h1 class="text-2xl md:text-3xl font-serif font-bold text-foreground">Pesan Layanan Antar - Jemput Laundry</h1>
        <p class="text-xs md:text-sm text-gray-500 mt-1">
            Pilih paket layanan sesuai kecepatan yang Anda butuhkan. <strong>Sistem antrian pengerjaan operator otomatis mengutamakan paket Prioritas, lalu VVIP, lalu VIP, kemudian Reguler.</strong>
        </p>
    </div>

    <!-- Main Order Form -->
    <div class="bg-white rounded-3xl p-6 md:p-10 border border-border shadow-md space-y-8">
        <form action="{{ route('customer.order.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- 1. Selection of 4 Package Tiers -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-foreground">
                    1. Pilih Paket Layanan & Prioritas Antrean:
                </label>

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach($packages as $pkg)
                        <label class="cursor-pointer">
                            <input type="radio" name="package_code" value="{{ $pkg->code }}" x-model="selectedPackage" 
                                @change="selectedPrice = {{ $pkg->price_per_kg }}; estimatedHours = {{ $pkg->estimated_hours }}; priorityLevel = {{ $pkg->priority_level }}; packageName = '{{ $pkg->name }}'" 
                                class="hidden peer" required>
                            
                            <div class="p-5 rounded-3xl border-2 border-border peer-checked:border-primary peer-checked:bg-primary/5 transition-all relative flex flex-col justify-between h-full hover:border-primary/50">
                                
                                @if($pkg->popular || $pkg->badge_text)
                                    <div class="absolute -top-3 right-4">
                                        <span class="bg-gradient-to-r from-primary to-emerald-600 text-white text-[10px] font-bold px-3 py-0.5 rounded-full shadow uppercase">
                                            {{ $pkg->badge_text ?? 'PALING LARIS' }}
                                        </span>
                                    </div>
                                @endif

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="font-serif font-bold text-base text-foreground">{{ $pkg->name }}</h3>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full 
                                            {{ $pkg->code === 'prioritas' ? 'bg-red-100 text-red-800' : ($pkg->code === 'vvip' ? 'bg-purple-100 text-purple-800' : ($pkg->code === 'vip' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800')) }}">
                                            Prioritas Level {{ $pkg->priority_level }}/4
                                        </span>
                                    </div>

                                    <div class="text-2xl font-bold font-serif text-primary mt-2">
                                        Rp {{ number_format($pkg->price_per_kg, 0, ',', '.') }}
                                        <span class="text-xs text-gray-500 font-normal">/kg</span>
                                    </div>

                                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                                        {{ $pkg->description }}
                                    </p>

                                    <!-- Priority Rule Note -->
                                    <div class="mt-3 text-[11px] font-semibold text-gray-700 bg-white p-2.5 rounded-xl border border-border">
                                        @if($pkg->code === 'prioritas')
                                            ⚡ <strong class="text-red-600">Top Priority:</strong> Dikerjakan seketika tanpa antre! Selesai kilat 4 jam.
                                        @elseif($pkg->code === 'vvip')
                                            ⭐ <strong class="text-purple-600">Prioritas Tinggi:</strong> Didahulukan di atas VIP & Reguler. Selesai 12 jam.
                                        @elseif($pkg->code === 'vip')
                                            ✨ <strong class="text-amber-600">Prioritas Cepat:</strong> Didahulukan di atas paket Reguler. Selesai 24 jam.
                                        @else
                                            🌿 <strong class="text-emerald-600">Reguler Standar:</strong> Antrean standar ekonomis. Selesai 2-3 hari.
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-border/60 text-[11px] text-gray-400 flex items-center justify-between">
                                    <span>Estimasi: <strong>{{ $pkg->estimated_hours }} Jam</strong></span>
                                    <span class="text-primary font-bold peer-checked:inline">Pilih Paket &rarr;</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 2. Customer Pickup Data -->
            <div class="space-y-4 pt-6 border-t border-border">
                <label class="block text-sm font-bold text-foreground">
                    2. Data Penjemputan Laundry:
                </label>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Pemesan *</label>
                        <input type="text" name="customer_name" value="{{ $customer->name }}" required class="w-full px-4 py-2.5 rounded-xl border border-border text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">No. WhatsApp Aktif *</label>
                        <input type="tel" name="customer_phone" value="{{ $customer->phone }}" required class="w-full px-4 py-2.5 rounded-xl border border-border text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alamat Penjemputan & Pengantaran *</label>
                    <textarea name="customer_address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-border text-sm focus:ring-2 focus:ring-primary outline-none">{{ $customer->address }}</textarea>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Estimasi Berat Laundry (Kg)</label>
                        <input type="number" name="weight" step="0.5" min="1" x-model.number="weight" class="w-full px-4 py-2.5 rounded-xl border border-border text-sm focus:ring-2 focus:ring-primary outline-none">
                        <span class="text-[10px] text-gray-400 mt-1 block">Supir akan menghitung jumlah potong pakaian saat penjemputan.</span>
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="bg-secondary/50 rounded-2xl p-4 border border-border flex flex-col justify-center">
                        <span class="text-[11px] text-gray-500 font-medium">Perkiraan Biaya (<span x-text="packageName"></span>):</span>
                        <div class="text-2xl font-bold font-serif text-primary mt-1">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(totalPrice)"></span>
                        </div>
                        <span class="text-[11px] text-emerald-700 font-semibold mt-0.5">
                            Prediksi Selesai: <span x-text="estimatedHours"></span> Jam setelah di-drop di laundry
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Catatan Tambahan untuk Supir / Operator (Opsional)</label>
                    <input type="text" name="customer_notes" placeholder="Contoh: kemeja putih jangan dicampur, pakaian batik cuci manual, ada selimut" class="w-full px-4 py-2.5 rounded-xl border border-border text-xs focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary to-emerald-600 hover:opacity-95 text-white font-bold rounded-2xl text-sm shadow-xl transition-all flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>Konfirmasi & Buat Pesanan Laundry</span>
            </button>
        </form>
    </div>
</div>
@endsection

