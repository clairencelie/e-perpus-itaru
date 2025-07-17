<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of peminjaman (untuk Staff/Admin).
     * Menampilkan semua daftar peminjaman yang perlu diproses/dilihat oleh Staff.
     */
    public function index(Request $request): View
    {
        $searchQuery = $request->input('search');
        $filterStatusPeminjaman = $request->input('status_peminjaman');
        $startDatePinjam = $request->input('start_date_pinjam');
        $endDatePinjam = $request->input('end_date_pinjam');

        $query = Peminjaman::with(['user', 'buku', 'denda']);

        // Logika Filter Pencarian Umum (User atau Buku)
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                // Cari di relasi User
                $q->whereHas('user', function ($qUser) use ($searchQuery) {
                    $qUser->where('nama', 'like', '%' . $searchQuery . '%')
                        ->orWhere('email', 'like', '%' . $searchQuery . '%')
                        ->orWhere('username', 'like', '%' . $searchQuery . '%');
                })
                    // Atau cari di relasi Buku
                    ->orWhereHas('buku', function ($qBuku) use ($searchQuery) {
                        $qBuku->where('judul', 'like', '%' . $searchQuery . '%')
                            ->orWhere('ISBN', 'like', '%' . $searchQuery . '%');
                    });
            });
        }

        // Logika Filter Status Peminjaman
        if ($filterStatusPeminjaman) {
            $query->where('status_peminjaman', $filterStatusPeminjaman);
        }

        // Logika Filter Tanggal Pinjam
        if ($startDatePinjam) {
            $query->whereDate('tanggal_pinjam', '>=', $startDatePinjam);
        }
        if ($endDatePinjam) {
            $query->whereDate('tanggal_pinjam', '<=', $endDatePinjam);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Display the specified resource (untuk Staff/Admin).
     * Menampilkan detail satu peminjaman.
     */
    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['user', 'buku', 'denda']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Handle an incoming request to approve a borrowing (untuk Staff/Admin).
     * Menyetujui permintaan peminjaman.
     */
    public function approve(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status_peminjaman !== 'pending') { // Asumsi ada status 'pending' untuk request baru
            return redirect()->back()->with('error', 'Peminjaman tidak dalam status pending.');
        }

        // Perbarui status buku
        $buku = $peminjaman->buku;
        if ($buku->stok_buku > 0) {
            $buku->decrement('stok_buku');
            $buku->status_ketersediaan = ($buku->stok_buku > 0) ? 'tersedia' : 'dipinjam';
            $buku->save();

            $peminjaman->status_peminjaman = 'dipinjam'; // Update status peminjaman
            $peminjaman->save();

            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disetujui!');
        } else {
            return redirect()->back()->with('error', 'Stok buku tidak mencukupi.');
        }
    }

    /**
     * Handle an incoming request to reject a borrowing (untuk Staff/Admin).
     * Menolak permintaan peminjaman.
     */
    public function reject(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status_peminjaman !== 'pending') { // Asumsi ada status 'pending'
            return redirect()->back()->with('error', 'Peminjaman tidak dalam status pending.');
        }

        $peminjaman->status_peminjaman = 'ditolak'; // Update status
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditolak.');
    }

    // --- Metode Khusus untuk Anggota (Diperbarui) ---

    /**
     * Process a new borrowing request directly from book card (for Members).
     * Memproses permintaan peminjaman buku langsung dari tombol di katalog.
     */
    public function requestBorrow(Request $request): RedirectResponse
    {
        $request->validate([
            'id_buku' => 'required|exists:buku,id_buku',
        ]);

        $buku = Buku::findOrFail($request->id_buku);
        $userId = Auth::id(); // Ambil ID user yang sedang login

        // --- PENGECEKAN BARU: CEK DENDA BELUM LUNAS ---
        // Cari denda yang terkait dengan user ini DAN statusnya 'belum_bayar'
        $outstandingFinesCount = Denda::whereHas('peminjaman', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })
            ->where('status_pembayaran', 'belum_bayar')
            ->count();

        if ($outstandingFinesCount > 0) {
            return redirect()->back()->with('error', 'Anda tidak dapat meminjam buku karena masih memiliki ' . $outstandingFinesCount . ' denda yang belum dilunasi.');
        }
        // --- AKHIR PENGECEKAN BARU ---

        if ($buku->file_PDF || $buku->tautan_digital) {
            return redirect()->back()->with('error', 'Buku ini adalah buku digital dan tidak perlu dipinjam secara fisik. Silakan gunakan tombol "Baca Buku" untuk mengaksesnya.');
        }

        // Periksa ketersediaan stok (logika yang sudah ada)
        if ($buku->stok_buku <= 0 || $buku->status_ketersediaan !== 'tersedia') {
            return redirect()->back()->with('error', 'Maaf, buku ini tidak tersedia untuk dipinjam saat ini.');
        }

        // Periksa apakah user sudah memiliki buku ini yang statusnya 'dipinjam' atau 'pending' (logika yang sudah ada)
        $existingPeminjaman = Peminjaman::where('id_user', $userId) // Gunakan $userId yang sudah diambil
            ->where('id_buku', $buku->id_buku)
            ->whereIn('status_peminjaman', ['dipinjam', 'pending', 'diajukan_pengembalian']) // Tambahkan 'diajukan_pengembalian' juga
            ->first();

        if ($existingPeminjaman) {
            return redirect()->back()->with('error', 'Anda sudah memiliki buku ini dalam status peminjaman aktif atau sedang menunggu persetujuan.');
        }

        $tanggalPinjam = Carbon::now();
        $tanggalJatuhTempo = $tanggalPinjam->copy()->addDays(7);

        Peminjaman::create([
            'id_user' => $userId,
            'id_buku' => $buku->id_buku,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            'status_peminjaman' => 'pending',
            'keterangan' => 'Permintaan peminjaman buku',
        ]);

        return redirect()->route('peminjaman.my_history')->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu persetujuan Staff.');
    }

    /**
     * Display current user's borrowing history (for Members).
     * Menampilkan riwayat peminjaman user yang sedang login.
     */
    public function myHistory(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Peminjaman::where('id_user', Auth::id())
            ->with(['buku', 'denda']);

        // Logika Filter Tanggal
        if ($startDate) {
            $query->whereDate('tanggal_pinjam', '>=', $startDate);
        }
        if ($endDate) {
            // Kita ingin tanggal_pinjam sampai akhir hari dari endDate
            $query->whereDate('tanggal_pinjam', '<=', $endDate);
        }

        $peminjamanSaya = $query->orderBy('created_at', 'desc')->get();

        return view('peminjaman.my-history', compact('peminjamanSaya'));
    }

    // Metode untuk anggota menandai pengembalian (Staff yang memproses fisik)
    // Tambahkan ini di dalam PeminjamanController class
    public function requestReturn(Request $request, $id_peminjaman)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id_peminjaman)
            ->where('id_user', Auth::id()) // Pastikan hanya anggota yang punya peminjaman ini
            ->where('status_peminjaman', 'dipinjam') // Hanya bisa diajukan jika statusnya 'dipinjam'
            ->first();

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Peminjaman tidak ditemukan atau tidak dapat diajukan pengembalian.');
        }

        // Ubah status peminjaman menjadi 'diajukan_pengembalian'
        $peminjaman->status_peminjaman = 'diajukan_pengembalian';
        $peminjaman->save();

        return redirect()->route('peminjaman.my_history')->with('success', 'Permintaan pengembalian buku berhasil diajukan. Menunggu verifikasi Staff.');
    }


    // Metode resource yang tidak digunakan atau dialihkan:
    // Hapus create() dan store() karena sudah tidak dipakai untuk form terpisah
    public function create(): RedirectResponse
    {
        abort(404);
    }
    public function store(Request $request): RedirectResponse
    {
        abort(404);
    }
    public function edit(Peminjaman $peminjaman)
    {
        // Pastikan peminjaman dalam status yang bisa diproses pengembalian
        // Status yang bisa diproses: dipinjam, terlambat, hilang, atau diajukan_pengembalian
        if (!in_array($peminjaman->status_peminjaman, ['dipinjam', 'terlambat', 'hilang', 'diajukan_pengembalian'])) {
            return redirect()->route('peminjaman.index')->with('error', 'Peminjaman tidak dalam status yang dapat diproses pengembalian.');
        }

        $peminjaman->load(['user', 'buku']); // Muat relasi user dan buku untuk ditampilkan di form
        return view('peminjaman.edit', compact('peminjaman')); // Mengembalikan view form pengembalian
    }
    public function update(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        // Logika validasi dan pemrosesan pengembalian yang sebelumnya ada di processReturn Anda
        if (!in_array($peminjaman->status_peminjaman, ['dipinjam', 'terlambat', 'hilang', 'diajukan_pengembalian'])) {
            return redirect()->back()->with('error', 'Buku belum dipinjam, sudah dikembalikan, atau tidak dalam status yang bisa diproses pengembalian.');
        }

        $request->validate([
            'kondisi_buku' => 'required|in:baik,rusak,hilang',
            'keterangan_pengembalian' => 'nullable|string|max:255',
        ]);

        $tanggalPengembalianAktual = Carbon::now();
        $peminjaman->tanggal_pengembalian = $tanggalPengembalianAktual;
        $peminjaman->keterangan = $request->keterangan_pengembalian;

        $dendaNominal = 0;
        $isTerlambat = false;
        $isRusak = false;

        $tanggalJatuhTempo = Carbon::parse($peminjaman->tanggal_jatuh_tempo);

        // --- PERHITUNGAN DENDA KETERLAMBATAN (VERSI SEDERHANA SESUAI PERMINTAAN) ---
        $daysLate = 0;
        // Jika tanggal pengembalian AKTUAL lebih besar dari tanggal jatuh tempo
        if ($tanggalPengembalianAktual->greaterThan($tanggalJatuhTempo)) {
            // Hitung selisih hari penuh antara tanggal pengembalian dan tanggal jatuh tempo.
            // Gunakan `startOfDay()` agar perbandingan hanya berdasarkan tanggal (mengabaikan waktu).
            $daysLate = -1 * $tanggalPengembalianAktual->startOfDay()->diffInDays($tanggalJatuhTempo->startOfDay());
            // dd([
            //     'selisih jatuh tempo' => $daysLate = $tanggalPengembalianAktual->startOfDay()->diffInDays($tanggalJatuhTempo->startOfDay())
            // ]);

            // Jika ada selisih waktu (walaupun belum 24 jam penuh) yang melewati tanggal jatuh tempo,
            // atau jika dikembalikan di hari yang sama tapi sudah melewati jam jatuh tempo,
            // itu berarti sudah terlambat setidaknya 1 hari.
            if ($daysLate == 0 && $tanggalPengembalianAktual->greaterThan($tanggalJatuhTempo)) {
                $daysLate += 1;
            }
            // Tambahkan 1 hari jika waktu pengembalian di hari yang berbeda setelah tanggal jatuh tempo
            // dan tidak sama dengan awal hari jatut tempo, misalnya jatuh tempo 13 Juli jam 10 pagi,
            // dikembalikan 14 Juli jam 9 pagi. diffInDays akan 1.
            // Tapi jika dikembalikan 14 Juli jam 10 pagi, diffInDays tetap 1.
            // Jadi jika lewat dari jatuh tempo, pastikan setidaknya dihitung 1 hari.
            // Jika selisihnya sudah lebih dari 0 hari (misal 1 hari, 2 hari), maka sudah benar.
        }

        if ($daysLate > 0) {
            $dendaNominal += $daysLate * 10000; // Contoh: Rp 1000 per hari keterlambatan
            $isTerlambat = true;
        }
        // --- AKHIR PERHITUNGAN DENDA KETERLAMBATAN ---


        if ($request->kondisi_buku === 'rusak') {
            $dendaNominal += 50000;
            $isRusak = true;
        } elseif ($request->kondisi_buku === 'hilang') {
            $dendaNominal += 150000;
            $isRusak = true;
            // $buku = $peminjaman->buku;
            // $buku->stok_buku = 0;
            // $buku->status_ketersediaan = 'hilang';
            // $buku->save();
        }

        if ($dendaNominal > 0) {
            Denda::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'nominal_denda' => $dendaNominal,
                'is_terlambat' => $isTerlambat,
                'is_rusak' => $isRusak,
                'status_pembayaran' => 'belum_bayar',
            ]); // Update status jika ada denda
            $peminjaman->status_peminjaman = 'dikembalikan'; // Jika tidak ada denda
        } else {
            $peminjaman->status_peminjaman = 'dikembalikan'; // Jika tidak ada denda
        }

        $peminjaman->save();

        // Kembalikan stok buku jika tidak hilang
        if ($request->kondisi_buku !== 'hilang') {
            $buku = $peminjaman->buku;
            $buku->increment('stok_buku');
            // $buku->status_ketersediaan = 'tersedia';
            $buku->save();
        }

        return redirect()->route('peminjaman.index')->with('success', 'Pengembalian buku berhasil diproses!');
    }
    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        abort(404);
    }
}
