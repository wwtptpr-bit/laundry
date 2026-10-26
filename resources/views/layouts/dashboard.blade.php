<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - CROWN & LUCKY Laundry</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
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
            background-color: #f4f6f3;
            color: #324227;
        }
        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f4f6f3] text-foreground antialiased flex flex-col" x-data="{ sidebarOpen: false }">

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

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-border sticky top-0 z-40 shadow-sm">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-gray-600 hover:text-primary">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center shadow">
                        <i data-lucide="waves" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="text-xl font-serif font-bold text-foreground hidden sm:inline">CROWN & LUCKY</span>
                </a>

                <!-- Role Badge -->
                @php
                    $roleBadges = [
                        'owner' => ['label' => '👑 OWNER (Full Akses & Harga)', 'class' => 'bg-amber-100 text-amber-900 border-amber-300'],
                        'admin' => ['label' => '🛡️ ADMIN (Monitoring Read-Only)', 'class' => 'bg-blue-100 text-blue-900 border-blue-300'],
                        'supir' => ['label' => '🚚 SUPIR (Antar - Jemput)', 'class' => 'bg-emerald-100 text-emerald-900 border-emerald-300'],
                        'oprator' => ['label' => '⚙️ OPERATOR (Pengerjaan & QC)', 'class' => 'bg-purple-100 text-purple-900 border-purple-300'],
                        'customer' => ['label' => '👤 CUSTOMER (Pelanggan)', 'class' => 'bg-teal-100 text-teal-900 border-teal-300'],
                    ];
                    $currentRole = Auth::user()->role ?? 'customer';
                    $roleInfo = $roleBadges[$currentRole] ?? ['label' => ucfirst($currentRole), 'class' => 'bg-gray-100 text-gray-800 border-gray-300'];
                @endphp
                <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $roleInfo['class'] }} ml-2">
                    {{ $roleInfo['label'] }}
                </span>
            </div>

            <!-- Right Controls: User Menu -->
            <div class="flex items-center gap-3">

                <!-- Back to Public Site -->
                <a href="{{ route('home') }}" class="text-xs text-gray-600 hover:text-primary font-medium hidden md:flex items-center gap-1 border border-border px-2.5 py-1.5 rounded-lg bg-gray-50">
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                    <span>Tampilan Publik</span>
                </a>

                <!-- User profile dropdown / Logout -->
                <div class="flex items-center gap-2 pl-2 border-l border-border">
                    <div class="w-8 h-8 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center text-xs">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-bold text-foreground leading-tight">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-muted-foreground">{{ Auth::user()->email }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                        @csrf
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Keluar">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-border transition-transform duration-200 flex flex-col pt-16 md:pt-0 shrink-0">
            <div class="p-4 flex-1 space-y-6 overflow-y-auto">
                <!-- Navigation Group tailored to Role -->
                <div>
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 px-3">
                        Navigasi Utama
                    </div>
                    <nav class="space-y-1">
                        @if($currentRole === 'owner')
                            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('owner.dashboard') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                <span>Ringkasan & KPI</span>
                            </a>
                            <a href="{{ route('owner.prices') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('owner.prices') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="tag" class="w-4 h-4"></i>
                                <span>Kelola Harga Paket</span>
                                <span class="ml-auto text-[10px] bg-amber-400/30 text-amber-900 font-bold px-1.5 py-0.5 rounded">Owner</span>
                            </a>
                            <a href="{{ route('owner.monitoring') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('owner.monitoring') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="activity" class="w-4 h-4"></i>
                                <span>Monitoring 4 Role</span>
                                <span class="ml-auto w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            </a>
                            <a href="{{ route('owner.orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('owner.orders') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                <span>Semua Transaksi</span>
                            </a>

                        @elseif($currentRole === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                <span>Dashboard Monitoring</span>
                            </a>
                            <a href="{{ route('admin.prices') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.prices') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                <span>Daftar Harga (Lihat)</span>
                                <span class="ml-auto text-[10px] bg-gray-200 text-gray-700 font-bold px-1.5 py-0.5 rounded">Read-only</span>
                            </a>
                            <a href="{{ route('admin.monitoring') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.monitoring') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="activity" class="w-4 h-4"></i>
                                <span>Audit Log Aktivitas</span>
                            </a>
                            <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.orders') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="list" class="w-4 h-4"></i>
                                <span>Pantau Pesanan</span>
                            </a>

                        @elseif($currentRole === 'supir')
                            <a href="{{ route('supir.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors bg-primary text-white shadow-md">
                                <i data-lucide="truck" class="w-4 h-4"></i>
                                <span>Dashboard Operasional</span>
                            </a>
                            <a href="#delivery-section" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-secondary">
                                <i data-lucide="bell-ring" class="w-4 h-4 text-emerald-600"></i>
                                <span>Siap Diantar (Notifikasi)</span>
                            </a>
                            <a href="#pickup-section" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-secondary">
                                <i data-lucide="plus-circle" class="w-4 h-4 text-blue-600"></i>
                                <span>Input Cucian Jemput</span>
                            </a>
                            <a href="#drop-section" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-secondary">
                                <i data-lucide="arrow-down-circle" class="w-4 h-4 text-indigo-600"></i>
                                <span>Drop di Laundry</span>
                            </a>

                        @elseif($currentRole === 'oprator')
                            <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors bg-primary text-white shadow-md">
                                <i data-lucide="sliders" class="w-4 h-4"></i>
                                <span>Antrian Prioritas Laundry</span>
                            </a>
                            <a href="#queue-section" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-secondary">
                                <i data-lucide="layers" class="w-4 h-4 text-purple-600"></i>
                                <span>Antrian Dikerjakan</span>
                            </a>
                            <a href="#discrepancy-section" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-secondary">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500"></i>
                                <span>Cek Selisih Hitung Ulang</span>
                            </a>

                        @elseif($currentRole === 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('customer.dashboard') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                                <span>Pesanan Aktif & Prediksi</span>
                            </a>
                            <a href="{{ route('customer.order.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('customer.order.create') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                <span>Pesan Laundry Baru</span>
                            </a>
                            <a href="{{ route('customer.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('customer.history') ? 'bg-primary text-white shadow-md' : 'text-gray-700 hover:bg-secondary' }}">
                                <i data-lucide="history" class="w-4 h-4"></i>
                                <span>Menu Riwayat (History)</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Info Box for Roles -->
                <div class="bg-secondary/60 rounded-2xl p-3.5 border border-border text-xs text-secondary-foreground space-y-1.5">
                    <div class="font-bold flex items-center gap-1.5">
                        <i data-lucide="info" class="w-4 h-4 text-primary"></i>
                        <span>Petunjuk Role</span>
                    </div>
                    @if($currentRole === 'owner')
                        <p class="text-[11px] text-gray-600 leading-relaxed">Anda adalah <strong>Owner</strong>. Anda dapat merubah harga paket di dashboard publik serta memonitor seluruh kegiatan dari 4 role lainnya.</p>
                    @elseif($currentRole === 'admin')
                        <p class="text-[11px] text-gray-600 leading-relaxed">Anda adalah <strong>Admin</strong>. Anda dapat memantau data seperti yang tampil pada Owner dalam mode read-only.</p>
                    @elseif($currentRole === 'supir')
                        <p class="text-[11px] text-gray-600 leading-relaxed">Anda adalah <strong>Supir</strong>. Input jumlah pakaian yang diambil, ubah status drop di laundry, dan antarkan pakaian yang siap diantar.</p>
                    @elseif($currentRole === 'oprator')
                        <p class="text-[11px] text-gray-600 leading-relaxed">Anda adalah <strong>Operator</strong>. Antrian diprioritaskan: Prioritas > VVIP > VIP > Reguler. Anda dapat menunda, hitung ulang barang, dan menandai selesai.</p>
                    @elseif($currentRole === 'customer')
                        <p class="text-[11px] text-gray-600 leading-relaxed">Anda adalah <strong>Pelanggan</strong>. Pilih paket Reguler, VIP, VVIP, atau Prioritas, pantau estimasi selesai, dan lihat riwayat.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-border">
                <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>

