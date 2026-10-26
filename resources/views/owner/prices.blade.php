@extends('layouts.dashboard')

@section('title', 'Kelola Harga Paket Layanan (Owner)')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 md:p-8 border border-border shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold uppercase tracking-wider mb-2">
                👑 Fitur Eksklusif Owner
            </div>
            <h1 class="text-2xl font-serif font-bold text-foreground">Kelola Harga Paket Layanan Publik</h1>
            <p class="text-xs text-gray-500 mt-1">Ubah tarif per kilogram, estimasi jam pengerjaan, dan daftar fitur yang ditampilkan kepada pengunjung di halaman depan.</p>
        </div>
        <a href="{{ route('home') }}#pricing" target="_blank" class="bg-secondary text-secondary-foreground hover:bg-secondary/80 font-bold px-4 py-2.5 rounded-xl text-xs border border-border flex items-center gap-1.5 transition-colors self-start sm:self-auto">
            <i data-lucide="eye" class="w-4 h-4"></i>
            <span>Preview Halaman Publik</span>
        </a>
    </div>

    <!-- Package Edit Cards -->
    <div class="grid md:grid-cols-2 gap-6">
        @foreach($packages as $pkg)
            <div class="bg-white rounded-3xl p-6 border-2 {{ $pkg->popular ? 'border-primary ring-2 ring-primary/20' : 'border-border' }} shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-border">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kode: {{ $pkg->code }}</span>
                        <h2 class="text-xl font-serif font-bold text-foreground">{{ $pkg->name }}</h2>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full 
                        {{ $pkg->code === 'prioritas' ? 'bg-red-100 text-red-800' : ($pkg->code === 'vvip' ? 'bg-purple-100 text-purple-800' : ($pkg->code === 'vip' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800')) }}">
                        Level Antrean {{ $pkg->priority_level }}/4
                    </span>
                </div>

                <form action="{{ route('owner.prices.update', $pkg->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Tampilan Paket</label>
                        <input type="text" name="name" value="{{ $pkg->name }}" required class="w-full px-4 py-2.5 rounded-xl border border-border text-sm font-semibold focus:ring-2 focus:ring-primary outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Harga Per Kg (Rp) *</label>
                            <input type="number" name="price_per_kg" value="{{ (int)$pkg->price_per_kg }}" min="100" step="500" required class="w-full px-4 py-2.5 rounded-xl border border-border text-base font-bold text-primary focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Estimasi Waktu (Jam) *</label>
                            <input type="number" name="estimated_hours" value="{{ $pkg->estimated_hours }}" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-border text-sm font-semibold focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Label Badge Khusus (Opsional)</label>
                        <input type="text" name="badge_text" value="{{ $pkg->badge_text }}" placeholder="Contoh: PALING LARIS / KILAT" class="w-full px-4 py-2.5 rounded-xl border border-border text-xs focus:ring-2 focus:ring-primary outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Daftar Fitur / Keunggulan (1 Baris = 1 Fitur)</label>
                        <textarea name="features_text" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-border text-xs leading-relaxed focus:ring-2 focus:ring-primary outline-none">@if(is_array($pkg->features)){{ implode("\n", $pkg->features) }}@endif</textarea>
                    </div>

                    <div class="flex items-center gap-6 pt-2">
                        <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                            <input type="checkbox" name="popular" value="1" {{ $pkg->popular ? 'checked' : '' }} class="rounded border-border text-primary focus:ring-primary">
                            <span>Tandai Paling Laris</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $pkg->is_active ? 'checked' : '' }} class="rounded border-border text-primary focus:ring-primary">
                            <span>Status Aktif di Publik</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary to-emerald-600 hover:opacity-95 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center justify-center gap-2 pt-3">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Simpan Perubahan Paket Ini</span>
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection

