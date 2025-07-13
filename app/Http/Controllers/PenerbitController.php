<?php

namespace App\Http\Controllers;

use App\Models\Penerbit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenerbitController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua penerbit.
     */
    public function index(): View
    {
        $penerbits = Penerbit::all(); // Ambil semua data penerbit
        return view('penerbit.index', compact('penerbits')); // Kirim data ke view
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk menambah penerbit baru.
     */
    public function create(): View
    {
        return view('penerbit.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data penerbit baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:255|unique:penerbit,nama_penerbit', // Pastikan unik
            'alamat' => 'nullable|string|max:255',
        ]);

        Penerbit::create($request->all()); // Buat penerbit baru

        return redirect()->route('penerbit.index')->with('success', 'Penerbit berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail satu penerbit.
     */
    public function show(Penerbit $penerbit): View
    {
        return view('penerbit.show', compact('penerbit'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit penerbit.
     */
    public function edit(Penerbit $penerbit): View
    {
        return view('penerbit.edit', compact('penerbit'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data penerbit di database.
     */
    public function update(Request $request, Penerbit $penerbit): RedirectResponse
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:255|unique:penerbit,nama_penerbit,' . $penerbit->id_penerbit . ',id_penerbit', // Unik kecuali dirinya sendiri
            'alamat' => 'nullable|string|max:255',
        ]);

        $penerbit->update($request->all()); // Perbarui data

        return redirect()->route('penerbit.index')->with('success', 'Penerbit berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus penerbit dari database.
     */
    public function destroy(Penerbit $penerbit): RedirectResponse
    {
        try {
            $penerbit->delete(); // Hapus penerbit
            return redirect()->route('penerbit.index')->with('success', 'Penerbit berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('penerbit.index')->with('error', 'Gagal menghapus penerbit. Mungkin ada buku yang terkait.');
        }
    }
}
