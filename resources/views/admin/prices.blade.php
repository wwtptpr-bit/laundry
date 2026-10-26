@extends('layouts.dashboard')

@section('title', 'Daftar Harga Paket (Admin Read-Only)')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-900 text-xs font-bold uppercase tracking-wider mb-2">
                🛡️ Mode Admin: Hanya Pantau (Read-Only)
            </div>
            <h1 class="text-2xl font-serif font-bold text-foreground">Daftar Harga Paket Layanan</h1>
            <p class="text-xs text-gray-500 mt-1">Sesuai aturan hak akses, admin hanya dapat memantau data harga yang aktif dan tidak dapat melakukan perubahan.</p>
        </div>
        <div class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-xs font-semibold border border-border">
            🔒 Hanya Owner yang dapat mengedit
        </div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($packages as $pkg)
            <div class="bg-white rounded-3xl p-6 border border-border shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-border">
                    <h3 class="font-bold text-base text-foreground font-serif">{{ $pkg->name }}</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $pkg->popular ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700' }}">
                        {{ $pkg->badge_text ?? 'Level '.$pkg->priority_level }}
                    </span>
                </div>

                <div>
                    <span class="text-[11px] text-gray-500 block">Tarif per Kilogram:</span>
                    <span class="text-2xl font-bold font-serif text-primary">
                        Rp {{ number_format($pkg->price_per_kg, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $pkg->unit }}</span>
                </div>

                <div class="space-y-1 text-xs text-gray-600 border-t border-border pt-3">
                    <div class="flex justify-between">
                        <span>Estimasi Pengerjaan:</span>
                        <strong>{{ $pkg->estimated_hours }} Jam</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Tingkat Prioritas:</span>
                        <strong>Level {{ $pkg->priority_level }}/4</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Status Publik:</span>
                        <strong class="{{ $pkg->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                            {{ $pkg->is_active ? 'Aktif Tayang' : 'Nonaktif' }}
                        </strong>
                    </div>
                </div>

                <div class="pt-3 border-t border-border">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Fitur Paket:</span>
                    <ul class="space-y-1.5 text-xs text-gray-600">
                        @if(is_array($pkg->features))
                            @foreach($pkg->features as $f)
                                <li class="flex items-start gap-1.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5"></i>
                                    <span>{{ $f }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

