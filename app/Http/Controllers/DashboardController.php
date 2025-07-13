<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBuku = 0;
        $totalAnggota = 0;
        $dendaBelumDibayar = 0;
        $totalPeminjamanAktif = 0; // Termasuk pending, dipinjam, diajukan_pengembalian, terlambat
        $peminjamanTerlambat = 0;
        $bukuTersedia = 0;

        // Hanya ambil data statistik jika user adalah Staff atau Kepala Perpustakaan
        if (Auth::user()->role === 'staff' || Auth::user()->role === 'kepala perpustakaan') {
            $totalBuku = Buku::count();
            $totalAnggota = User::where('role', 'anggota')->count(); // Hanya hitung role anggota
            $dendaBelumDibayar = Denda::where('status_pembayaran', 'belum_bayar')->count();
            $totalPeminjamanAktif = Peminjaman::whereIn('status_peminjaman', ['pending', 'dipinjam', 'diajukan_pengembalian', 'terlambat'])->count();
            $peminjamanTerlambat = Peminjaman::where('status_peminjaman', 'terlambat')->count();
            $bukuTersedia = Buku::where('stok_buku', '>', 0)
                ->where('status_ketersediaan', 'tersedia')
                ->count();
        }

        return view('dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'dendaBelumDibayar',
            'totalPeminjamanAktif',
            'peminjamanTerlambat',
            'bukuTersedia'
        ));
    }
}
