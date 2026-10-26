@extends('layouts.app')

@section('title', 'CROWN & LUCKY Laundry - Mitra Laundry Profesional Sejak 1997')

@section('content')

<!-- Hero Section -->
<section id="hero" class="relative pt-12 pb-24 px-4 overflow-hidden section-bg">
    <div class="container mx-auto">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 mb-4 px-4 py-2 bg-secondary rounded-full shadow-sm">
                    <span class="text-secondary-foreground font-semibold text-xs md:text-sm">✨ Berpengalaman Sejak 1997</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-serif font-bold mb-6 leading-tight text-foreground">
                    Pengalaman Menciptakan
                    <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent"> Kesempurnaan.</span>
                </h1>

                <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
                    Nikmati layanan laundry cepat, higienis, dan andal. Pakaian Anda bersih, wangi, dan rapi sempurna, didukung oleh puluhan tahun pengalaman profesional.
                </p>

                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="#order" class="bg-gradient-to-r from-primary to-emerald-600 hover:opacity-95 text-white font-semibold text-base md:text-lg px-8 py-3.5 rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        <span>Pesan Sekarang</span>
                    </a>
                    <a href="#services" class="border-2 border-primary text-primary hover:bg-secondary font-semibold text-base md:text-lg px-8 py-3.5 rounded-2xl transition-colors">
                        Lihat Layanan
                    </a>
                    <a href="{{ route('public.track') }}" class="border border-border bg-white text-gray-700 hover:text-primary hover:bg-gray-50 font-medium text-base px-6 py-3.5 rounded-2xl transition-colors flex items-center gap-2 shadow-sm">
                        <i data-lucide="search" class="w-4 h-4 text-primary"></i>
                        <span>Lacak Pesanan</span>
                    </a>
                </div>

                <!-- 3 Highlights -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-border">
                    <div class="text-center group">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-2 text-white shadow-md group-hover:scale-105 transition-transform">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs md:text-sm font-bold text-foreground">Layanan Cepat</p>
                        <p class="text-[11px] text-gray-500">Mulai dari 4 jam</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-2 text-white shadow-md group-hover:scale-105 transition-transform">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs md:text-sm font-bold text-foreground">Garansi Bersih</p>
                        <p class="text-[11px] text-gray-500">100% Wangi & Higienis</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-2 text-white shadow-md group-hover:scale-105 transition-transform">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs md:text-sm font-bold text-foreground">Tepat Waktu</p>
                        <p class="text-[11px] text-gray-500">Antar jemput terjadwal</p>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="relative">
                <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                    <img src="https://images.unsplash.com/photo-1572501403253-c113f1d6c7fe?auto=format&fit=crop&w=800&q=80" alt="Pakaian Bersih Rapi CROWN & LUCKY Laundry" class="w-full h-auto object-cover transform hover:scale-105 transition-transform duration-700">
                </div>
                <!-- Ambient Blurs -->
                <div class="absolute -top-8 -left-8 w-64 h-64 bg-emerald-200/50 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -bottom-8 -right-8 w-64 h-64 bg-primary/20 rounded-full blur-3xl -z-10"></div>
            </div>
        </div>
    </div>
</section>

<!-- Company Profile Section -->
<section id="company-profile" class="py-20 px-4 bg-white border-y border-border">
    <div class="container mx-auto">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-block px-3 py-1 bg-secondary rounded-full text-xs font-bold text-secondary-foreground">PROFIL KAMI</div>
                <h2 class="text-3xl md:text-5xl font-serif font-bold text-foreground leading-tight">
                    Mitra Terpercaya Sejak <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">1997</span>
                </h2>
                <p class="text-gray-600 leading-relaxed text-base">
                    CROWN & LUCKY Laundry didirikan dengan komitmen untuk menghadirkan standar pencucian tertinggi bagi pakaian keluarga, linen hotel, dan gordyn berharga Anda. Dengan teknologi ramah lingkungan, deterjen premium antibakteri, serta armada antar-jemput profesional, kami menjaga pakaian Anda tetap bersih awet dan segar tahan lama.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4">
                    <div class="bg-background p-4 rounded-2xl border border-border text-center">
                        <div class="text-2xl md:text-3xl font-bold text-primary font-serif">{{ $stats['experience_years'] }}+</div>
                        <div class="text-xs text-gray-500 font-medium mt-1">Tahun Pengalaman</div>
                    </div>
                    <div class="bg-background p-4 rounded-2xl border border-border text-center">
                        <div class="text-2xl md:text-3xl font-bold text-primary font-serif">{{ number_format($stats['total_completed']) }}+</div>
                        <div class="text-xs text-gray-500 font-medium mt-1">Cucian Selesai</div>
                    </div>
                    <div class="bg-background p-4 rounded-2xl border border-border text-center">
                        <div class="text-2xl md:text-3xl font-bold text-primary font-serif">{{ $stats['satisfaction_rate'] }}%</div>
                        <div class="text-xs text-gray-500 font-medium mt-1">Kepuasan Pelanggan</div>
                    </div>
                    <div class="bg-background p-4 rounded-2xl border border-border text-center">
                        <div class="text-2xl md:text-3xl font-bold text-primary font-serif">5 Role</div>
                        <div class="text-xs text-gray-500 font-medium mt-1">Sistem Terpadu</div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="rounded-3xl overflow-hidden shadow-xl border-4 border-secondary">
                    <img src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?auto=format&fit=crop&w=800&q=80" alt="Mesin Laundry Profesional CROWN & LUCKY" class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-24 px-4 section-bg water-effect" style="--ripple-delay: 1s">
    <div class="container mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl md:text-5xl font-serif font-bold mb-4">
                Layanan <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Terbaik</span> Kami
            </h2>
            <p class="text-gray-600 text-base md:text-lg">
                Pilihan layanan lengkap untuk memenuhi semua kebutuhan kebersihan pakaian pribadi, bisnis, dan institusi Anda.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Cuci & Setrika -->
            <div class="bg-white/90 backdrop-blur rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-green-100/60 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-md group-hover:scale-110 transition-transform">
                    <i data-lucide="shirt" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-foreground">Cuci & Setrika</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Pakaian dicuci bersih higienis dan disetrika uap rapi bergaransi dengan standar profesional tertinggi.
                </p>
            </div>

            <!-- Dry Cleaning -->
            <div class="bg-white/90 backdrop-blur rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-emerald-100/60 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-md group-hover:scale-110 transition-transform">
                    <i data-lucide="wind" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-foreground">Dry Cleaning</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Perawatan khusus tanpa air untuk pakaian berbahan sensitif seperti jas formal, gaun pesta, sutra, dan kebaya.
                </p>
            </div>

            <!-- Layanan Kilat / Prioritas -->
            <div class="bg-white/90 backdrop-blur rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-teal-100/60 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-md group-hover:scale-110 transition-transform">
                    <i data-lucide="sparkles" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-foreground">Layanan Prioritas</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Layanan super cepat 4 - 12 jam untuk kebutuhan mendesak Anda. Dikerjakan paling awal tanpa antre.
                </p>
            </div>

            <!-- Antar Jemput Gratis -->
            <div class="bg-white/90 backdrop-blur rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-lime-100/60 group hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-lime-600 to-emerald-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-md group-hover:scale-110 transition-transform">
                    <i data-lucide="truck" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-foreground">Antar Jemput Gratis</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Supir kami siap menjemput dan mengantarkan pakaian langsung ke pintu rumah Anda secara terjadwal.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Hotel & Gordyn Expertise -->
<section class="py-20 px-4 bg-white border-t border-border">
    <div class="container mx-auto space-y-20">
        <!-- Hotel Expertise -->
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="rounded-3xl overflow-hidden shadow-2xl border-4 border-secondary">
                <img src="https://images.unsplash.com/photo-1496890185067-72d7d9af66b1?auto=format&fit=crop&w=800&q=80" alt="Linen Hotel CROWN & LUCKY" class="w-full h-auto object-cover">
            </div>
            <div class="space-y-6">
                <div class="inline-block px-3 py-1 bg-secondary rounded-full text-xs font-bold text-secondary-foreground">HOTEL EXPERTISE</div>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-foreground">
                    Laundry <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Hotel & Villa Expert</span>
                </h2>
                <p class="text-gray-600 text-base leading-relaxed">
                    Spesialis pencucian dan perawatan linen hotel, duvet cover, handuk, seragam karyawan, dan taplak meja F&B dengan standar kebersihan bintang 5.
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Sprei & Duvet Cover
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Handuk Lembut Higienis
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Seragam Staf Rapi
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Bebas Noda & Alergen
                    </div>
                </div>
            </div>
        </div>

        <!-- Gordyn Expertise -->
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6 order-2 md:order-1">
                <div class="inline-block px-3 py-1 bg-secondary rounded-full text-xs font-bold text-secondary-foreground">GORDYN SPECIALIST</div>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-foreground">
                    Spesialis Perawatan <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Gordyn & Vitrase</span>
                </h2>
                <p class="text-gray-600 text-base leading-relaxed">
                    Perawatan khusus gordyn tebal, vitrase halus, hingga perbaikan keliman tanpa merusak lipatan estetika ruangan Anda. Tim kami juga melayani bongkar pasang di tempat.
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Layanan Bongkar Pasang
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Bebas Debu & Tungau
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Setrika Uap Vertikal
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Reparasi Kait & Jahitan
                    </div>
                </div>
            </div>
            <div class="rounded-3xl overflow-hidden shadow-2xl border-4 border-secondary order-1 md:order-2">
                <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=800&q=80" alt="Perawatan Gordyn CROWN & LUCKY" class="w-full h-auto object-cover">
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section (Loaded Dynamically from Database - Owner Can Edit!) -->
<section id="pricing" class="py-24 px-4 section-bg water-effect" style="--ripple-delay: 3s">
    <div class="container mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-5xl font-serif font-bold mb-4">
                Harga <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Bersahabat</span> & Transparan
            </h2>
            <p class="text-gray-600 text-base md:text-lg">
                Pilih paket yang paling sesuai dengan kecepatan dan kebutuhan Anda. Urutan antrean pengerjaan diprioritaskan: <span class="font-bold text-red-600">Prioritas</span> &gt; <span class="font-bold text-purple-600">VVIP</span> &gt; <span class="font-bold text-amber-600">VIP</span> &gt; <span class="font-bold text-emerald-600">Reguler</span>.
            </p>
            @auth
                @if(Auth::user()->role === 'owner')
                    <div class="mt-4 inline-flex items-center gap-2 bg-amber-50 text-amber-900 border border-amber-300 px-4 py-2 rounded-xl text-xs font-semibold">
                        <i data-lucide="edit-3" class="w-4 h-4 text-amber-600"></i>
                        <span>Anda Login sebagai Owner: Anda dapat mengubah harga paket ini di</span>
                        <a href="{{ route('owner.prices') }}" class="underline font-bold text-amber-700 hover:text-amber-900">Dashboard Owner &rarr;</a>
                    </div>
                @endif
            @endauth
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto">
            @foreach($packages as $pkg)
                <div class="relative bg-white rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 border-2 {{ $pkg->popular ? 'border-primary ring-2 ring-primary/20' : 'border-border' }} flex flex-col justify-between hover:-translate-y-2">
                    
                    @if($pkg->popular || $pkg->badge_text)
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2">
                            <span class="bg-gradient-to-r from-primary to-emerald-500 text-white px-4 py-1 rounded-full text-xs font-bold shadow-md uppercase tracking-wider">
                                {{ $pkg->badge_text ?? 'PALING LARIS' }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <!-- Header Card -->
                        <div class="text-center pb-6 border-b border-border">
                            <h3 class="text-xl font-serif font-bold text-foreground mb-1">{{ $pkg->name }}</h3>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                {{ $pkg->code === 'prioritas' ? 'bg-red-100 text-red-800' : ($pkg->code === 'vvip' ? 'bg-purple-100 text-purple-800' : ($pkg->code === 'vip' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800')) }}">
                                Level Antrian {{ $pkg->priority_level }}/4
                            </span>
                            
                            <div class="flex items-baseline justify-center gap-1 mt-4">
                                <span class="text-gray-400 text-sm font-semibold">Rp</span>
                                <span class="text-4xl font-bold bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">
                                    {{ number_format($pkg->price_per_kg, 0, ',', '.') }}
                                </span>
                                <span class="text-gray-500 text-xs">{{ $pkg->unit }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Estimasi pengerjaan: <strong>{{ $pkg->estimated_hours }} Jam</strong></p>
                        </div>

                        <!-- Features list -->
                        <ul class="space-y-3 py-6 text-xs text-gray-600">
                            @if(is_array($pkg->features))
                                @foreach($pkg->features as $feature)
                                    <li class="flex items-start gap-2.5">
                                        <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <i data-lucide="check" class="w-3 h-3"></i>
                                        </div>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <!-- CTA Button -->
                    <a href="#order" onclick="selectPackage('{{ $pkg->code }}', '{{ $pkg->name }}', {{ $pkg->price_per_kg }})" class="block w-full text-center py-2.5 px-4 rounded-xl text-xs font-bold transition-all shadow-sm {{ $pkg->popular ? 'bg-gradient-to-r from-primary to-emerald-600 text-white hover:opacity-90 shadow-md' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80' }}">
                        Pilih {{ $pkg->name }}
                    </a>
                </div>
            @endforeach
        </div>

        <p class="text-center text-xs text-gray-500 mt-8">*Gratis antar-jemput untuk minimal 5 kg dalam radius operasional 3 km.</p>
    </div>
</section>

<!-- Order Form Section (Pesan Antar Jemput) -->
<section id="order" class="py-24 px-4 bg-gradient-to-b from-white to-[#f4f7f1]" x-data="{
    selectedPackage: '{{ $packages->first()->code ?? 'reguler' }}',
    selectedPrice: {{ $packages->first()->price_per_kg ?? 6000 }},
    estimatedWeight: 5,
    get totalPrice() {
        return this.selectedPrice * this.estimatedWeight;
    }
}">
    <div class="container mx-auto max-w-4xl">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1 bg-secondary rounded-full text-xs font-bold text-secondary-foreground mb-2">FORMULIR ONLINE</div>
            <h2 class="text-3xl md:text-5xl font-serif font-bold mb-4">
                Pesan Antar <span class="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Jemput</span>
            </h2>
            <p class="text-gray-600 text-base md:text-lg">
                Isi form di bawah ini dan supir kami akan segera meluncur ke lokasi Anda.
            </p>
        </div>

        <div class="bg-white rounded-3xl p-6 md:p-10 shadow-2xl border border-border">
            <form action="{{ route('public.order.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Package Selection Radio Cards -->
                <div>
                    <label class="block text-sm font-bold text-foreground mb-3">Pilih Paket Layanan & Prioritas Antrean:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($packages as $pkg)
                            <label class="cursor-pointer">
                                <input type="radio" name="package_code" value="{{ $pkg->code }}" x-model="selectedPackage" @change="selectedPrice = {{ $pkg->price_per_kg }}" class="hidden peer" required>
                                <div class="p-3.5 rounded-2xl border-2 border-border peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center">
                                    <div class="text-xs font-bold text-foreground">{{ $pkg->name }}</div>
                                    <div class="text-sm font-extrabold text-primary mt-1">Rp {{ number_format($pkg->price_per_kg, 0, ',', '.') }}<span class="text-[10px] text-gray-500 font-normal">/kg</span></div>
                                    <div class="text-[10px] text-gray-500 mt-0.5">Selesai: {{ $pkg->estimated_hours }} jam</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap *</label>
                        <input type="text" id="name" name="name" value="{{ Auth::user()->name ?? old('name') }}" placeholder="Contoh: Ibu Rina / Bpk. Budi" required class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WhatsApp Aktif *</label>
                        <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? old('phone') }}" placeholder="Contoh: 081234567890" required class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Penjemputan Lengkap *</label>
                    <textarea id="address" name="address" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, atau patokan lokasi..." required class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">{{ Auth::user()->address ?? old('address') }}</textarea>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label for="estimated_weight" class="block text-sm font-semibold text-gray-700 mb-1.5">Estimasi Berat (Kg)</label>
                        <input type="number" id="estimated_weight" name="estimated_weight" step="0.5" min="1" x-model.number="estimatedWeight" class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
                        <span class="text-[11px] text-gray-500">*Berat riil dan jumlah potong pakaian akan dihitung ulang secara transparan oleh supir & operator.</span>
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="bg-secondary/60 p-4 rounded-2xl border border-border flex flex-col justify-center">
                        <div class="text-xs text-gray-500 font-medium">Estimasi Biaya:</div>
                        <div class="text-2xl font-bold text-primary font-serif">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(totalPrice)"></span>
                        </div>
                        <div class="text-[11px] text-emerald-700 font-medium mt-1">
                            ✨ Termasuk Antar-Jemput & Cuci Higienis
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Khusus (Opsional)</label>
                    <input type="text" id="notes" name="notes" placeholder="Misal: ada baju sutra, jangan campur warna putih, titip di security" class="w-full px-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
                </div>

                <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-primary to-emerald-600 hover:opacity-95 text-white font-bold text-base shadow-lg transition-all flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    <span>Kirim Pesanan Laundry Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Feedback / Testimonials -->
<section class="py-20 px-4 bg-white border-t border-border">
    <div class="container mx-auto max-w-6xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif font-bold text-foreground">Apa Kata <span class="text-primary">Pelanggan Kami</span></h2>
            <p class="text-gray-500 text-sm mt-1">Ulasan nyata dari pelanggan setia CROWN & LUCKY</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-background p-6 rounded-2xl border border-border">
                <div class="flex text-amber-400 gap-1 mb-3">
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                </div>
                <p class="text-gray-600 text-xs italic mb-4 leading-relaxed">"Paket Prioritas 4 jam bener-bener penyelamat pas ada acara mendadak! Pakaian super wangi, licin rapi, dan supirnya tepat waktu banget."</p>
                <div class="font-bold text-xs text-foreground">Ibu Amanda Sutanto</div>
                <div class="text-[11px] text-gray-400">Pelanggan Apartemen Kebayoran</div>
            </div>
            <div class="bg-background p-6 rounded-2xl border border-border">
                <div class="flex text-amber-400 gap-1 mb-3">
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                </div>
                <p class="text-gray-600 text-xs italic mb-4 leading-relaxed">"Suka banget sama transparansi jumlah potong bajunya. Pas supir jemput dihitung, di workshop dihitung lagi. Nggak pernah ada baju hilang atau tertukar."</p>
                <div class="font-bold text-xs text-foreground">Bpk. Darmawan</div>
                <div class="text-[11px] text-gray-400">Pengusaha Hotel Melati</div>
            </div>
            <div class="bg-background p-6 rounded-2xl border border-border">
                <div class="flex text-amber-400 gap-1 mb-3">
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                </div>
                <p class="text-gray-600 text-xs italic mb-4 leading-relaxed">"Pencucian gordyn rumah saya hasilnya luar biasa bersih, lipatannya tetap terjaga bagus. Pelayanan CROWN & LUCKY dari dulu memang tidak pernah mengecewakan."</p>
                <div class="font-bold text-xs text-foreground">Ibu Nurhaliza</div>
                <div class="text-[11px] text-gray-400">Pelanggan Sejak 2005</div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Us Section -->
<section id="contact" class="py-20 px-4 section-bg">
    <div class="container mx-auto max-w-5xl">
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-border grid md:grid-cols-2 gap-8 items-center">
            <div>
                <span class="text-xs font-bold text-primary uppercase tracking-wider">HUBUNGI KAMI</span>
                <h3 class="text-3xl font-serif font-bold text-foreground mt-1 mb-4">Siap Melayani Anda Setiap Hari</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    Punya pertanyaan seputar tarif grosir hotel, kontrak gordyn kantor, atau jadwal antar jemput? Hubungi tim kami sekarang juga.
                </p>
                <div class="space-y-4 text-sm">
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center text-primary">
                            <i data-lucide="phone-call" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Telepon / WhatsApp</div>
                            <div class="font-bold">0812-3456-7890</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center text-primary">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Email Resmi</div>
                            <div class="font-bold">info@crownlucky.com</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center text-primary">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Workshop & Outlet Pusat</div>
                            <div class="font-bold">Jl. Raya Utama No. 88, Kebayoran Baru, Jakarta Selatan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-background p-6 rounded-2xl border border-border text-center space-y-4">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-full flex items-center justify-center mx-auto shadow-md">
                    <i data-lucide="message-square" class="w-8 h-8"></i>
                </div>
                <h4 class="text-lg font-bold text-foreground">Chat Cepat via WhatsApp</h4>
                <p class="text-xs text-gray-500">Konsultasikan kebutuhan laundry Anda langsung dengan customer service kami yang ramah dan responsif.</p>
                <a href="https://wa.me/6281234567890?text=Halo%20CROWN%20%26%20LUCKY%20Laundry,%20saya%20ingin%20konsultasi" target="_blank" class="inline-flex items-center justify-center gap-2 w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-colors shadow">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span>Buka WhatsApp Sekarang</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function selectPackage(code, name, price) {
        const orderSection = document.getElementById('order');
        if (orderSection) {
            orderSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
</script>
@endpush

