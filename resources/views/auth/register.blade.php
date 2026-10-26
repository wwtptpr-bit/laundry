@extends('layouts.app')

@section('title', 'Daftar Akun Pelanggan - CROWN & LUCKY Laundry')

@section('content')
<div class="py-12 px-4 bg-background min-h-[85vh] flex items-center justify-center section-bg">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-border p-8 md:p-10">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center text-white mx-auto shadow-md mb-3">
                <i data-lucide="user-plus" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-serif font-bold text-foreground">Daftar Akun Pelanggan</h2>
            <p class="text-xs text-gray-500 mt-1">Dapatkan kemudahan pesan antar-jemput dan pantau riwayat laundry Anda.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-3.5 rounded-xl border border-red-200 text-xs mb-4">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Dewi Lestari" class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alamat Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="dewi@gmail.com" class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi *</label>
                    <input type="password" id="password" name="password" required placeholder="Min. 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Ulangi Sandi *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi sandi" class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">No. WhatsApp *</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="082123456789" class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alamat Domisili Antar-Jemput *</label>
                <textarea id="address" name="address" rows="2" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..." class="w-full px-4 py-2.5 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm">{{ old('address') }}</textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-primary to-emerald-600 hover:opacity-95 text-white font-bold rounded-xl text-sm shadow-md transition-all flex items-center justify-center gap-2 mt-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Selesaikan Pendaftaran</span>
            </button>
        </form>

        <div class="pt-6 border-t border-border mt-6 text-center text-xs text-gray-500">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection

