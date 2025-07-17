<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(Request $request): View
    {
        $searchQuery = $request->input('search');

        $query = Kategori::query();

        if ($searchQuery) {
            $query->where('nama_kategori', 'like', '%' . $searchQuery . '%');
        }

        $kategoris = $query->orderBy('nama_kategori')->get(); // Urutkan berdasarkan nama kategori

        return view('kategori.index', compact('kategoris'));
    }

    public function create(): View
    {
        return view('kategori.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        Kategori::create($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(Kategori $kategori): View
    {
        return view('kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori): View
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $kategori->id_kategori . ',id_kategori',
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        try {
            $kategori->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('kategori.index')->with('error', 'Gagal menghapus kategori. Mungkin ada buku yang terkait.');
        }
    }
}
