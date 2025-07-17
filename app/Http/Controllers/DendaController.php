<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DendaController extends Controller
{
    /**
     * Display a listing of the resource (untuk Staff/Admin).
     * Menampilkan daftar semua denda.
     */
    public function index(Request $request): View
    {
        $statusPembayaran = $request->input('status_pembayaran');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Denda::with('peminjaman.user', 'peminjaman.buku');

        // Logika Filter Status Pembayaran
        if ($statusPembayaran) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Logika Filter Tanggal Dibuat
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $dendas = $query->orderBy('created_at', 'desc')->get();

        return view('denda.index', compact('dendas'));
    }

    /**
     * Display the specified resource (untuk Staff/Admin).
     * Menampilkan detail satu denda.
     */
    public function show(Denda $denda): View
    {
        $denda->load('peminjaman.user', 'peminjaman.buku');
        return view('denda.show', compact('denda'));
    }

    /**
     * Show the form for editing the specified resource (untuk Staff/Admin).
     * Menampilkan form untuk mengedit denda (misal: mencatat pembayaran).
     */
    public function edit(Denda $denda): View
    {
        return view('denda.edit', compact('denda'));
    }

    /**
     * Update the specified resource in storage (untuk Staff/Admin).
     * Memperbarui data denda (misal: status pembayaran).
     */
    public function update(Request $request, Denda $denda): RedirectResponse
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,lunas',
            'tanggal_bayar' => 'nullable|date',
        ]);

        $denda->status_pembayaran = $request->status_pembayaran;
        if ($request->status_pembayaran === 'lunas' && !$denda->tanggal_bayar) {
            $denda->tanggal_bayar = now(); // Isi otomatis jika lunas dan belum ada tanggal
        } elseif ($request->status_pembayaran === 'lunas' && $request->tanggal_bayar) {
            $denda->tanggal_bayar = $request->tanggal_bayar;
        } elseif ($request->status_pembayaran === 'belum_bayar') {
            $denda->tanggal_bayar = null; // Kosongkan tanggal jika dibatalkan lunas
        }

        $denda->save();

        return redirect()->route('denda.index')->with('success', 'Status denda berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage (untuk Staff/Admin).
     * Menghapus denda (jarang dilakukan, kecuali salah input).
     */
    public function destroy(Denda $denda): RedirectResponse
    {
        try {
            $denda->delete();
            return redirect()->route('denda.index')->with('success', 'Denda berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('denda.index')->with('error', 'Gagal menghapus denda.');
        }
    }

    // --- Metode Khusus untuk Anggota ---

    /**
     * Display current user's fines (for Members).
     * Menampilkan daftar denda user yang sedang login.
     */
    public function myDenda(Request $request): View
    {
        $statusPembayaran = $request->input('status_pembayaran');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Denda::whereHas('peminjaman', function ($qPeminjaman) {
            $qPeminjaman->where('id_user', Auth::id());
        })
            ->with('peminjaman.buku');

        // Logika Filter Status Pembayaran
        if ($statusPembayaran) {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Logika Filter Tanggal Dibuat
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $dendaSaya = $query->orderBy('created_at', 'desc')->get();

        return view('denda.my-denda', compact('dendaSaya'));
    }

    // Metode resource yang tidak digunakan atau dialihkan:
    public function create(): RedirectResponse
    {
        abort(404);
    }
    public function store(Request $request): RedirectResponse
    {
        abort(404);
    }
}
