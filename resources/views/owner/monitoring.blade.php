@extends('layouts.dashboard')

@section('title', 'Monitoring Aktivitas 4 Role (Owner)')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-bold uppercase tracking-wider mb-2">
                <i data-lucide="activity" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Audit Trail & Monitoring</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-foreground">Monitoring Aktivitas 4 Role Lainnya</h1>
            <p class="text-xs text-gray-500 mt-1">Pantau seluruh kegiatan, input jumlah pakaian supir, hitung ulang operator, penundaan proses, hingga pesanan pelanggan.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Live Feed Aktif</span>
            </span>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-border shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Role Tabs -->
        <div class="flex flex-wrap gap-1.5">
            <a href="{{ route('owner.monitoring') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ !request('role') ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua Role ({{ $rolesCount['all'] }})
            </a>
            <a href="{{ route('owner.monitoring', ['role' => 'admin']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'admin' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                🛡️ Admin ({{ $rolesCount['admin'] }})
            </a>
            <a href="{{ route('owner.monitoring', ['role' => 'supir']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'supir' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                🚚 Supir ({{ $rolesCount['supir'] }})
            </a>
            <a href="{{ route('owner.monitoring', ['role' => 'oprator']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'oprator' ? 'bg-purple-600 text-white shadow-sm' : 'bg-purple-50 text-purple-800 hover:bg-purple-100' }}">
                ⚙️ Operator ({{ $rolesCount['oprator'] }})
            </a>
            <a href="{{ route('owner.monitoring', ['role' => 'customer']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors {{ request('role') === 'customer' ? 'bg-teal-600 text-white shadow-sm' : 'bg-teal-50 text-teal-800 hover:bg-teal-100' }}">
                👤 Customer ({{ $rolesCount['customer'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('owner.monitoring') }}" class="flex gap-2">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas / kode..." class="px-3.5 py-1.5 rounded-xl border border-border text-xs outline-none focus:ring-2 focus:ring-primary w-48">
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
                        <span>{{ $log->created_at->translatedFormat('d M Y, H:i:s') }} ({{ $log->created_at->diffForHumans() }})</span>
                    </div>
                </div>

                <p class="text-xs text-gray-700 leading-relaxed">{{ $log->description }}</p>

                <div class="flex flex-wrap items-center justify-between text-[11px] text-gray-400 pt-1">
                    <div>
                        Oleh: <strong class="text-gray-700">{{ $log->user_name }}</strong> (ID: {{ $log->user_id ?? 'System' }})
                    </div>
                    @if($log->ip_address)
                        <div>IP Address: {{ $log->ip_address }}</div>
                    @endif
                </div>

                @if($log->metadata)
                    <div class="bg-gray-50 rounded-xl p-2.5 text-[10px] font-mono text-gray-600 border border-border">
                        <strong>Metadata Telemetry:</strong> {{ json_encode($log->metadata) }}
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-border shadow-sm text-gray-400 text-xs">
                Tidak ada data log aktivitas yang cocok dengan filter.
            </div>
        @endforelse

        <div class="pt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection

