@extends('layouts.app')

@section('title', 'Login Sistem Laundry - CROWN & LUCKY')

@section('content')
<div class="py-16 px-4 bg-background min-h-[85vh] flex items-center justify-center section-bg">
    <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl border border-border p-8 md:p-10 space-y-8">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center shadow-lg mx-auto text-white">
                <i data-lucide="waves" class="w-7 h-7"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold text-foreground">CROWN & LUCKY LAUNDRY</h1>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                Silakan masuk menggunakan email dan kata sandi akun Anda untuk mengakses sistem operasional.
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-3.5 rounded-2xl border border-red-200 text-xs flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Standard Login Form -->
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Alamat Email Akun
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@crownlucky.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm bg-gray-50/50">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-3 rounded-xl border border-border focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-sm bg-gray-50/50">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-border text-primary focus:ring-primary">
                    <span>Ingat sesi saya</span>
                </label>
                <span class="text-gray-400">Password default: <code class="bg-gray-100 px-1 py-0.5 rounded font-mono text-[11px]">password</code></span>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-primary to-emerald-600 hover:opacity-95 text-white font-bold rounded-2xl text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-4 h-4"></i>
                <span>Masuk ke Dashboard</span>
            </button>
        </form>

        <!-- Role Credentials Reference (Informational Only - No 1-click bypass) -->
        <div class="bg-secondary/40 rounded-2xl p-4 border border-border space-y-2 text-xs">
            <div class="font-bold text-foreground text-[11px] uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="shield-check" class="w-4 h-4 text-primary"></i>
                <span>Referensi Akun 5 Role:</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] text-gray-600">
                <div class="bg-white p-2 rounded-xl border border-border">
                    <span class="font-bold text-gray-800">👑 Owner:</span> <code class="text-primary font-mono text-[10px]">owner@crownlucky.com</code>
                </div>
                <div class="bg-white p-2 rounded-xl border border-border">
                    <span class="font-bold text-gray-800">🛡️ Admin:</span> <code class="text-blue-600 font-mono text-[10px]">admin@crownlucky.com</code>
                </div>
                <div class="bg-white p-2 rounded-xl border border-border">
                    <span class="font-bold text-gray-800">🚚 Supir:</span> <code class="text-emerald-600 font-mono text-[10px]">supir@crownlucky.com</code>
                </div>
                <div class="bg-white p-2 rounded-xl border border-border">
                    <span class="font-bold text-gray-800">⚙️ Operator:</span> <code class="text-purple-600 font-mono text-[10px]">operator@crownlucky.com</code>
                </div>
                <div class="bg-white p-2 rounded-xl border border-border sm:col-span-2">
                    <span class="font-bold text-gray-800">👤 Customer:</span> <code class="text-teal-600 font-mono text-[10px]">customer@gmail.com</code>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-border flex items-center justify-between text-xs text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Beranda</span>
            </a>
            <div>
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-primary hover:underline">Daftar Pelanggan</a>
            </div>
        </div>

    </div>
</div>
@endsection
