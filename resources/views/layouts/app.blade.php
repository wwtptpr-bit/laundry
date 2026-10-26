<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CROWN & LUCKY Laundry - Mitra Laundry Profesional Sejak 1997')</title>
    <meta name="description" content="CROWN & LUCKY Laundry: berpengalaman sejak 1997. Layanan cuci, setrika, dry cleaning, dan perbaikan gordyn dengan layanan cepat dan andal.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for instant reliable rendering) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#527938',
                            hover: '#43642d',
                            light: '#88a770',
                            50: '#f4f7f1',
                            100: '#e5eedf',
                            500: '#527938',
                            600: '#43642d',
                        },
                        secondary: {
                            DEFAULT: '#e9eee5',
                            foreground: '#2f4322',
                        },
                        background: '#f8faf7',
                        foreground: '#324227',
                        border: '#dbe5d4',
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8faf7;
            color: #324227;
        }
        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .section-bg {
            position: relative;
            overflow: hidden;
        }
        .section-bg::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='%23527938' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
            z-index: -2;
        }
        .water-effect::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(82, 121, 56, 0.08) 0%, rgba(82, 121, 56, 0) 60%);
            border-radius: 50%;
            transform-origin: center;
            animation: water-ripple 6s ease-out infinite;
            animation-delay: var(--ripple-delay, 0s);
            z-index: -1;
        }
        @keyframes water-ripple {
            0% { transform: translate(-50%, -50%) scale(0); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 0; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-background text-foreground antialiased selection:bg-primary/20 selection:text-primary">

    <!-- Flash Notifications -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#527938',
                    timer: 4000
                });
            });
        </script>
    @endif
    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: "{{ session('warning') }}",
                    confirmButtonColor: '#eab308'
                });
            });
        </script>
    @endif
    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: "{{ session('info') }}",
                    confirmButtonColor: '#3b82f6'
                });
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ef4444'
                });
            });
        </script>
    @endif

    <!-- Header Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-border/80 transition-all duration-300" x-data="{ mobileMenu: false, dropdownMenu: false }">
        <div class="container mx-auto px-4 py-3.5">
            <div class="flex items-center justify-between">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <i data-lucide="waves" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-serif font-bold tracking-tight text-foreground block">CROWN & LUCKY</span>
                        <span class="text-[10px] tracking-wider uppercase text-muted-foreground font-semibold block -mt-1">Laundry Service Since 1997</span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('home') }}#hero" class="text-foreground hover:text-primary px-3 py-2 text-sm font-medium transition-colors">Beranda</a>
                    <a href="{{ route('home') }}#services" class="text-foreground hover:text-primary px-3 py-2 text-sm font-medium transition-colors">Layanan</a>
                    <a href="{{ route('home') }}#company-profile" class="text-foreground hover:text-primary px-3 py-2 text-sm font-medium transition-colors">Profil Kami</a>
                    <a href="{{ route('home') }}#pricing" class="text-foreground hover:text-primary px-3 py-2 text-sm font-medium transition-colors">Harga Paket</a>
                    <a href="{{ route('home') }}#contact" class="text-foreground hover:text-primary px-3 py-2 text-sm font-medium transition-colors">Hubungi Kami</a>
                    <a href="{{ route('public.track') }}" class="text-primary hover:text-primary-hover px-3 py-2 text-sm font-semibold flex items-center gap-1.5 transition-colors">
                        <i data-lucide="search" class="w-4 h-4"></i> Lacak Status
                    </a>

                    <!-- Auth Button / Dashboard -->
                    <div class="ml-3 flex items-center gap-2">
                        @auth
                            @php
                                $dashboardUrl = match(Auth::user()->role) {
                                    'owner' => route('owner.dashboard'),
                                    'admin' => route('admin.dashboard'),
                                    'supir' => route('supir.dashboard'),
                                    'oprator' => route('operator.dashboard'),
                                    'customer' => route('customer.dashboard'),
                                    default => route('home')
                                };
                            @endphp
                            <a href="{{ $dashboardUrl }}" class="flex items-center gap-2 bg-secondary text-secondary-foreground hover:bg-secondary/80 px-4 py-2 rounded-xl text-sm font-medium transition-colors border border-border">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 text-primary"></i>
                                <span>Dashboard ({{ ucfirst(Auth::user()->role) }})</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-gray-500 hover:text-red-600 transition-colors" title="Keluar">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-foreground hover:text-primary font-medium px-4 py-2 rounded-xl text-sm transition-colors border border-border bg-white shadow-sm hover:shadow">
                                <i data-lucide="log-in" class="w-4 h-4 text-primary"></i>
                                <span>Login (5 Role)</span>
                            </a>
                            <a href="{{ route('home') }}#order" class="bg-gradient-to-r from-primary to-emerald-500 hover:opacity-90 text-white font-medium px-5 py-2 rounded-xl text-sm shadow-md transition-all flex items-center gap-1.5">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                <span>Pesan Sekarang</span>
                            </a>
                        @endauth
                    </div>
                </nav>

                <!-- Mobile Hamburger Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-foreground focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenu"></i>
                    <i data-lucide="x" class="w-6 h-6" x-show="mobileMenu"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-b border-border px-4 py-4 space-y-3">
            <a href="{{ route('home') }}#hero" @click="mobileMenu = false" class="block text-foreground hover:text-primary font-medium py-1">Beranda</a>
            <a href="{{ route('home') }}#services" @click="mobileMenu = false" class="block text-foreground hover:text-primary font-medium py-1">Layanan</a>
            <a href="{{ route('home') }}#company-profile" @click="mobileMenu = false" class="block text-foreground hover:text-primary font-medium py-1">Profil Kami</a>
            <a href="{{ route('home') }}#pricing" @click="mobileMenu = false" class="block text-foreground hover:text-primary font-medium py-1">Harga</a>
            <a href="{{ route('home') }}#contact" @click="mobileMenu = false" class="block text-foreground hover:text-primary font-medium py-1">Hubungi Kami</a>
            <a href="{{ route('public.track') }}" class="block text-primary font-semibold py-1">Lacak Status Pesanan</a>
            <div class="pt-2 border-t border-border flex flex-col gap-2">
                @auth
                    <a href="{{ $dashboardUrl ?? route('home') }}" class="w-full text-center bg-secondary text-secondary-foreground py-2.5 rounded-xl font-medium">Buka Dashboard ({{ ucfirst(Auth::user()->role) }})</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center text-red-600 py-2 font-medium">Keluar / Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center border border-border py-2.5 rounded-xl font-medium text-foreground bg-white">Login 5 Role Akun</a>
                    <a href="{{ route('home') }}#order" @click="mobileMenu = false" class="w-full text-center bg-gradient-to-r from-primary to-emerald-500 text-white py-2.5 rounded-xl font-medium shadow">Pesan Antar Jemput</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- WhatsApp Floating Button -->
    <div class="fixed bottom-6 right-6 z-40" x-data="{ open: false }">
        <div x-show="open" @click.away="open = false" x-transition class="absolute bottom-16 right-0 w-72 bg-white rounded-2xl shadow-2xl border border-border p-4 mb-2">
            <div class="flex items-center gap-3 border-b border-border pb-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-foreground">Customer Service</h4>
                    <p class="text-xs text-emerald-600 flex items-center gap-1 font-medium"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span> Online Siap Membantu</p>
                </div>
            </div>
            <p class="text-xs text-muted-foreground mb-3">Halo! Ada yang bisa kami bantu seputar antar jemput laundry hari ini?</p>
            <a href="https://wa.me/6281234567890?text=Halo%20Crown%20Lucky%20Laundry,%20saya%20ingin%20tanya%20layanan%20laundry" target="_blank" class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 rounded-xl text-xs transition-colors">
                Mulai Chat WhatsApp
            </a>
        </div>
        <button @click="open = !open" class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-full flex items-center justify-center shadow-xl hover:scale-110 transition-transform">
            <i data-lucide="message-circle" class="w-7 h-7"></i>
        </button>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-16 px-4 border-t border-gray-800">
        <div class="container mx-auto">
            <div class="grid md:grid-cols-4 gap-10 mb-12">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center text-white">
                            <i data-lucide="waves" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xl font-serif font-bold text-white">CROWN & LUCKY</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Mitra laundry terpercaya Anda sejak 1997. Melayani laundry pribadi, hotel, villa, dan gordyn dengan dedikasi serta kualitas terbaik.
                    </p>
                    <div class="flex items-center gap-3 text-emerald-400 font-semibold text-xs">
                        <i data-lucide="shield-check" class="w-4 h-4"></i> Terdaftar & Bergaransi Bersih
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-white text-base mb-4 font-serif">Layanan Unggulan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}#services" class="hover:text-emerald-400 transition-colors">Cuci & Setrika Reguler</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-emerald-400 transition-colors">Layanan VIP 24 Jam</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-emerald-400 transition-colors">Layanan VVIP 12 Jam</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-emerald-400 transition-colors">Sultan Prioritas 4 Jam</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-emerald-400 transition-colors">Laundry Linen Hotel & Gordyn</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white text-base mb-4 font-serif">Akses Sistem</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors">Login 5 Role (Owner/Admin/Supir/Operator/Customer)</a></li>
                        <li><a href="{{ route('public.track') }}" class="hover:text-emerald-400 transition-colors">Pelacakan Status Laundry Realtime</a></li>
                        <li><a href="{{ route('home') }}#pricing" class="hover:text-emerald-400 transition-colors">Daftar Harga Terbaru</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-emerald-400 transition-colors">Pendaftaran Akun Pelanggan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white text-base mb-4 font-serif">Kontak & Workshop</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5"></i>
                            <span>Jl. Raya Utama No. 88, Kebayoran, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i data-lucide="phone" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                            <span>(021) 7890-1234 / 0812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i data-lucide="clock" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                            <span>Setiap Hari: 07.00 - 21.00 WIB</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                <p>&copy; 1997 - {{ date('Y') }} CROWN & LUCKY Laundry. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-6">
                    <span class="text-emerald-500 font-medium">Laravel 11 & PHP 8.3 Powered</span>
                    <span>Sistem Multi-Role: Owner, Admin, Supir, Operator, Customer</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>

