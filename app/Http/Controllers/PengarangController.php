<?php

namespace App\Http\Controllers;

use App\Models\Pengarang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengarangController extends Controller
{
    public function index(): View
    {
        $pengarangs = Pengarang::all();
        return view('pengarang.index', compact('pengarangs'));
    }

    public function create(): View
    {
        return view('pengarang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_pengarang' => 'required|string|max:255|unique:pengarang,nama_pengarang',
        ]);

        Pengarang::create($request->all());
        return redirect()->route('pengarang.index')->with('success', 'Pengarang berhasil ditambahkan!');
    }

    public function show(Pengarang $pengarang): View
    {
        return view('pengarang.show', compact('pengarang'));
    }

    public function edit(Pengarang $pengarang): View
    {
        return view('pengarang.edit', compact('pengarang'));
    }

    public function update(Request $request, Pengarang $pengarang): RedirectResponse
    {
        $request->validate([
            'nama_pengarang' => 'required|string|max:255|unique:pengarang,nama_pengarang,' . $pengarang->id_pengarang . ',id_pengarang',
        ]);

        $pengarang->update($request->all());
        return redirect()->route('pengarang.index')->with('success', 'Pengarang berhasil diperbarui!');
    }

    public function destroy(Pengarang $pengarang): RedirectResponse
    {
        try {
            $pengarang->delete();
            return redirect()->route('pengarang.index')->with('success', 'Pengarang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('pengarang.index')->with('error', 'Gagal menghapus pengarang. Mungkin ada buku yang terkait.');
        }
    }
}
