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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, redirect ke halaman login
            return redirect()->route('login');
        }

        $user = Auth::user(); // Dapatkan objek pengguna yang sedang login

        // Periksa apakah peran pengguna (user->role) cocok dengan peran yang dibutuhkan ($role)
        // Di migrasi users kita pakai enum role: ['anggota', 'staff', 'kepala perpustakaan']
        // 'staff' bisa mengakses fungsionalitas 'staff'
        // 'kepala perpustakaan' juga bisa mengakses fungsionalitas 'staff' DAN 'kepala perpustakaan'
        // Mari kita asumsikan hirarki: kepala perpustakaan > staff > anggota
        // Jadi, jika role yang diminta adalah 'staff', maka 'staff' dan 'kepala perpustakaan' bisa masuk.
        // Jika role yang diminta adalah 'kepala perpustakaan', maka hanya 'kepala perpustakaan' yang bisa masuk.

        if ($role === 'staff') {
            if ($user->role === 'staff' || $user->role === 'kepala perpustakaan') {
                return $next($request); // Lanjutkan permintaan
            }
        } elseif ($role === 'kepala perpustakaan') {
            if ($user->role === 'kepala perpustakaan') {
                return $next($request); // Lanjutkan permintaan
            }
        }
        // Anda bisa tambahkan 'anggota' jika ada rute yang hanya untuk anggota
        // elseif ($role === 'anggota') {
        //     if ($user->role === 'anggota') {
        //         return $next($request);
        //     }
        // }

        // Jika peran tidak sesuai, redirect atau tampilkan error
        abort(403, 'Akses tidak diizinkan. Anda tidak memiliki peran yang sesuai.'); // Akses ditolak (Forbidden)
    }
}
