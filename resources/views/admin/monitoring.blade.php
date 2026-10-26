@extends('layouts.dashboard')

@section('title', 'Monitoring Aktivitas 4 Role (Admin)')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-900 text-xs font-bold uppercase tracking-wider mb-2">
                🛡️ Audit Monitoring Admin (Read-Only)
            </div>
            <h1 class="text-2xl font-serif font-bold text-foreground">Pemantauan Aktivitas Seluruh Pengguna</h1>
            <p class="text-xs text-gray-500 mt-1">Admin memantau seluruh aktivitas dari Supir, Operator, Pelanggan, dan Owner secara realtime.</p>
        </div>
        <div class="text-xs text-gray-500">
            Total Aktivitas: <strong>{{ $rolesCount['all'] }} Event</strong>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-border shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-wrap gap-1.5">
            <a href="{{ route('admin.monitoring') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ !request('role') ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua ({{ $rolesCount['all'] }})
            </a>
            <a href="{{ route('admin.monitoring', ['role' => 'supir']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'supir' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                🚚 Supir ({{ $rolesCount['supir'] }})
            </a>
            <a href="{{ route('admin.monitoring', ['role' => 'oprator']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'oprator' ? 'bg-purple-600 text-white shadow-sm' : 'bg-purple-50 text-purple-800 hover:bg-purple-100' }}">
                ⚙️ Operator ({{ $rolesCount['oprator'] }})
            </a>
            <a href="{{ route('admin.monitoring', ['role' => 'customer']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'customer' ? 'bg-teal-600 text-white shadow-sm' : 'bg-teal-50 text-teal-800 hover:bg-teal-100' }}">
                👤 Customer ({{ $rolesCount['customer'] }})
            </a>
            <a href="{{ route('admin.monitoring', ['role' => 'owner']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'owner' ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                👑 Owner ({{ $rolesCount['owner'] }})
            </a>
        </div>

        <form method="GET" action="{{ route('admin.monitoring') }}" class="flex gap-2">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..." class="px-3.5 py-1.5 rounded-xl border border-border text-xs outline-none focus:ring-2 focus:ring-primary w-48">
            <button type="submit" class="bg-primary text-white px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-primary-hover">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
            </button>
        </form>
    </div>

    <!-- Logs List -->
    <div class="space-y-3">
        @forelse($logs as $log)
            <div class="bg-white rounded-2xl p-5 border border-border shadow-sm hover:shadow-md transition-shadow space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2.5 border-b border-border/60">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg border {{ $log->role_badge_class }}">
                            {{ strtoupper($log->role) }}
                        </span>
                        <h3 class="text-sm font-bold text-foreground">{{ $log->title }}</h3>
                        @if($log->order_code)
                            <span class="text-xs font-mono font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">
                                {{ $log->order_code }}
                            </span>
                        @endif
                    </div>
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        <span>{{ $log->created_at->translatedFormat('d M Y, H:i:s') }}</span>
                    </div>
                </div>

                <p class="text-xs text-gray-700 leading-relaxed">{{ $log->description }}</p>

                <div class="flex flex-wrap items-center justify-between text-[11px] text-gray-400 pt-1">
                    <div>
                        Oleh: <strong class="text-gray-700">{{ $log->user_name }}</strong>
                    </div>
                    @if($log->ip_address)
                        <div>IP: {{ $log->ip_address }}</div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-border shadow-sm text-gray-400 text-xs">
                Tidak ada data log aktivitas.
            </div>
        @endforelse

        <div class="pt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection

