<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect to their appropriate dashboard if unauthorized for this role
        return match($user->role) {
            'owner' => redirect()->route('owner.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'supir' => redirect()->route('supir.dashboard'),
            'oprator' => redirect()->route('operator.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman ini.')
        };
    }
}

