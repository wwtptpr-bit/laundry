<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            ActivityLog::log($user, 'USER_LOGIN', 'Pengguna Masuk', "Pengguna {$user->name} ({$user->role_label}) berhasil login.");

            return $this->redirectBasedOnRole($user->role)
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function quickLogin($role)
    {
        $validRoles = ['owner', 'admin', 'supir', 'oprator', 'customer'];
        if (!in_array($role, $validRoles)) {
            return redirect()->route('login')->with('error', 'Role tidak valid.');
        }

        $user = User::where('role', $role)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', "Pengguna dengan role {$role} belum terdaftar.");
        }

        Auth::login($user);
        request()->session()->regenerate();

        ActivityLog::log($user, 'USER_QUICK_LOGIN', 'Quick Login Pengguna', "Login cepat sebagai {$user->name} ({$user->role_label})");

        return $this->redirectBasedOnRole($user->role)
            ->with('success', "Berhasil masuk sebagai {$user->name} ({$user->role_label})");
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        Auth::login($user);
        request()->session()->regenerate();

        ActivityLog::log($user, 'USER_REGISTER', 'Pendaftaran Pelanggan Baru', "Akun baru dibuat: {$user->name} ({$user->email})");

        return redirect()->route('customer.dashboard')
            ->with('success', 'Akun berhasil didaftarkan! Selamat datang di CROWN & LUCKY Laundry.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::log($user, 'USER_LOGOUT', 'Pengguna Keluar', "Pengguna {$user->name} telah keluar dari sistem.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar.');
    }

    protected function redirectBasedOnRole(string $role)
    {
        return match($role) {
            'owner' => redirect()->route('owner.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'supir' => redirect()->route('supir.dashboard'),
            'oprator' => redirect()->route('operator.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => redirect()->route('home')
        };
    }
}

