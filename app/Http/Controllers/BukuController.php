<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Penerbit;
use App\Models\Pengarang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource (untuk Staff/Admin).
     * Menampilkan daftar semua buku (untuk manajemen oleh Staff).
     */
    public function index(): View
    {
        $bukus = Buku::with(['penerbit', 'pengarangs', 'kategoris'])->get(); // Eager load relasi
        return view('buku.index', compact('bukus'));
    }

    /**
     * Show the form for creating a new resource (untuk Staff/Admin).
     * Menampilkan form untuk menambah buku baru.
     */
    public function create(): View
    {
        $penerbits = Penerbit::all();
        $pengarangs = Pengarang::all();
        $kategoris = Kategori::all();
        return view('buku.create', compact('penerbits', 'pengarangs', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage (untuk Staff/Admin).
     * Menyimpan data buku baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ISBN' => 'required|string|max:255|unique:buku,ISBN',
            'stok_buku' => 'required|integer|min:0',
            'deskripsi_buku' => 'nullable|string',
            'status_ketersediaan' => 'required|in:tersedia,dipinjam,hilang',
            'file_PDF' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
            'tautan_digital' => 'nullable|url|max:255',
            'id_penerbit' => 'nullable|exists:penerbit,id_penerbit',
            'pengarang_ids' => 'nullable|array',
            'pengarang_ids.*' => 'exists:pengarang,id_pengarang',
            'kategori_ids' => 'nullable|array',
            'kategori_ids.*' => 'exists:kategori,id_kategori',
        ]);

        $bukuData = $request->except(['pengarang_ids', 'kategori_ids', 'file_PDF']);

        // Handle file upload untuk file PDF (UBAH INI)
        if ($request->hasFile('file_PDF')) {
            // Simpan file PDF menggunakan disk 'public' di folder 'buku_pdf'
            $pathPdf = $request->file('file_PDF')->store('buku_pdf', 'public'); // <-- UBAH BARIS INI
            $bukuData['file_PDF'] = $pathPdf; // Simpan path relatif dari disk public di database
        }

        // Handle file upload untuk cover
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public'); // Simpan di storage/app/public/covers
            $bukuData['cover'] = $path; // Simpan path relatif di database
        }

        $buku = Buku::create($bukuData);

        // Sinkronisasi relasi many-to-many
        if ($request->has('pengarang_ids')) {
            $buku->pengarangs()->sync($request->input('pengarang_ids'));
        }
        if ($request->has('kategori_ids')) {
            $buku->kategoris()->sync($request->input('kategori_ids'));
        }

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource (untuk Staff/Admin).
     * Menampilkan detail satu buku.
     */
    public function show(Buku $buku): View
    {
        // Otomatis eager load relasi di model, tapi bisa juga di sini:
        $buku->load(['penerbit', 'pengarangs', 'kategoris']);
        return view('buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource (untuk Staff/Admin).
     * Menampilkan form untuk mengedit buku.
     */
    public function edit(Buku $buku): View
    {
        $penerbits = Penerbit::all();
        $pengarangs = Pengarang::all();
        $kategoris = Kategori::all();
        $buku->load(['pengarangs', 'kategoris']); // Load relasi yang sudah ada untuk form
        return view('buku.edit', compact('buku', 'penerbits', 'pengarangs', 'kategoris'));
    }

    /**
     * Update the specified resource in storage (untuk Staff/Admin).
     * Memperbarui data buku di database.
     */
    public function update(Request $request, Buku $buku): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'ISBN' => 'required|string|max:255|unique:buku,ISBN,' . $buku->id_buku . ',id_buku',
            'stok_buku' => 'required|integer|min:0',
            'deskripsi_buku' => 'nullable|string',
            'status_ketersediaan' => 'required|in:tersedia,dipinjam,hilang',
            'file_PDF' => 'nullable|file|mimes:pdf|max:10240',
            'tautan_digital' => 'nullable|url|max:255',
            'id_penerbit' => 'nullable|exists:penerbit,id_penerbit',
            'pengarang_ids' => 'nullable|array',
            'pengarang_ids.*' => 'exists:pengarang,id_pengarang',
            'kategori_ids' => 'nullable|array',
            'kategori_ids.*' => 'exists:kategori,id_kategori',
        ]);

        $bukuData = $request->except(['pengarang_ids', 'kategori_ids', 'file_PDF']);

        // Handle file upload untuk file PDF (UBAH INI)
        if ($request->hasFile('file_PDF')) {
            // Hapus file PDF lama jika ada
            if ($buku->file_PDF && Storage::disk('public')->exists($buku->file_PDF)) { // <-- Gunakan disk('public')
                Storage::disk('public')->delete($buku->file_PDF); // <-- Gunakan disk('public')
            }
            // Simpan file PDF menggunakan disk 'public' di folder 'buku_pdf'
            $pathPdf = $request->file('file_PDF')->store('buku_pdf', 'public'); // <-- UBAH BARIS INI
            $bukuData['file_PDF'] = $pathPdf; // Simpan path relatif dari disk public di database
        }

        // Handle file upload untuk cover
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada (penting untuk path yang benar)
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) { // <-- Gunakan disk('public')
                Storage::disk('public')->delete($buku->cover); // <-- Gunakan disk('public')
            }

            // Simpan file menggunakan disk 'public'
            $path = $request->file('cover')->store('covers', 'public'); // <-- UBAH BARIS INI
            $bukuData['cover'] = $path; // <-- UBAH BARIS INI
        }

        $buku->update($bukuData);

        // Sinkronisasi relasi many-to-many
        $buku->pengarangs()->sync($request->input('pengarang_ids', [])); // Kosongkan jika tidak ada pilihan
        $buku->kategoris()->sync($request->input('kategori_ids', [])); // Kosongkan jika tidak ada pilihan

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage (untuk Staff/Admin).
     * Menghapus buku dari database.
     */
    public function destroy(Buku $buku): RedirectResponse
    {
        try {
            // Hapus file PDF terkait jika ada (TAMBAHKAN INI)
            if ($buku->file_PDF && Storage::disk('public')->exists($buku->file_PDF)) {
                Storage::disk('public')->delete($buku->file_PDF);
            }
            // Hapus file cover jika ada
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) { // <-- Gunakan disk('public')
                Storage::disk('public')->delete($buku->cover); // <-- Gunakan disk('public')
            }

            $buku->delete();
            return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('buku.index')->with('error', 'Gagal menghapus buku. Mungkin ada peminjaman terkait.');
        }
    }

    // --- Metode Khusus untuk Anggota (Katalog Publik) ---

    /**
     * Display a listing of books for public view (for Members).
     * Menampilkan daftar buku untuk katalog publik yang bisa dilihat Anggota.
     */
    public function indexPublic(): View
    {
        $bukus = Buku::where('stok_buku', '>', 0) // Hanya tampilkan buku yang tersedia
            ->where('status_ketersediaan', 'tersedia')
            ->with(['penerbit', 'pengarangs', 'kategoris'])
            ->get();
        return view('katalog.index', compact('bukus')); // View baru untuk katalog
    }

    /**
     * Display the specified book for public view (for Members).
     * Menampilkan detail satu buku untuk katalog publik.
     */
    public function showPublic(Buku $buku): View
    {
        if ($buku->stok_buku <= 0 || $buku->status_ketersediaan !== 'tersedia') {
            abort(404); // Atau redirect dengan pesan error
        }
        $buku->load(['penerbit', 'pengarangs', 'kategoris']);
        return view('katalog.show', compact('buku')); // View baru untuk detail katalog
    }
}
