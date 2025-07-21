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
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response // <-- Ubah 'string ...$roles' menjadi 'string $requiredRole'
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Cek apakah peran pengguna SAMA PERSIS dengan peran yang dibutuhkan oleh rute ini.
        if ($userRole !== $requiredRole) {
            abort(403, 'Akses tidak diizinkan. Anda tidak memiliki peran yang sesuai.');
        }

        return $next($request);
    }
}
