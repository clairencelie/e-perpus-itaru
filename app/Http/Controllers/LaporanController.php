<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Peminjaman; // Import Model Peminjaman
use App\Models\Denda;      // Import Model Denda
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // <-- TAMBAHKAN INI
use Illuminate\Database\Eloquent\Builder; // Untuk query Eloquent

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua laporan.
     */
    public function index(): View
    {
        $laporans = Laporan::orderBy('created_at', 'desc')->get();
        return view('laporan.index', compact('laporans'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat laporan baru.
     */
    public function create(): View
    {
        return view('laporan.create');
    }

    /**
     * Store a newly created resource in storage.
     * Membuat dan menyimpan laporan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'jenis_laporan' => 'required|in:operasional,transaksi,stok_buku',
            'rentang_tanggal_awal' => 'nullable|date',
            'rentang_tanggal_akhir' => 'nullable|date|after_or_equal:rentang_tanggal_awal',
        ]);

        // Ini adalah contoh sederhana. Logika sebenarnya untuk generate laporan
        // (misalnya PDF/Excel) akan lebih kompleks dan biasanya dilakukan di Job/Service.
        $namaFile = $request->jenis_laporan . '_' . Carbon::now()->format('Ymd_His') . '.txt';
        $kontenLaporan = "Laporan Jenis: " . $request->jenis_laporan . "\n";
        if ($request->rentang_tanggal_awal && $request->rentang_tanggal_akhir) {
            $kontenLaporan .= "Dari Tanggal: " . $request->rentang_tanggal_awal . " Hingga: " . $request->rentang_tanggal_akhir . "\n";
        }
        $kontenLaporan .= "Ini adalah contoh konten laporan. Implementasi nyata akan mengambil data dari DB.";

        // Simpan laporan sebagai file teks sederhana di storage
        $path = 'public/laporan/' . $namaFile;
        Storage::put($path, $kontenLaporan);

        Laporan::create([
            'tanggal_dibuat' => Carbon::now(),
            'jenis_laporan' => $request->jenis_laporan,
            'nama_file' => $namaFile,
            'path_file' => Storage::url($path), // Simpan URL publik
            'rentang_tanggal_awal' => $request->rentang_tanggal_awal,
            'rentang_tanggal_akhir' => $request->rentang_tanggal_akhir,
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail satu laporan.
     */
    public function show(Laporan $laporan): View
    {
        return view('laporan.show', compact('laporan'));
    }

    /**
     * Downloads the specified report file.
     * Mengunduh file laporan.
     */
    public function download(Laporan $laporan) // Ini metode kustom, bukan bagian dari resource standard
    {
        if (Storage::exists(str_replace('/storage', 'public', $laporan->path_file))) {
            return Storage::download(str_replace('/storage', 'public', $laporan->path_file), $laporan->nama_file);
        } else {
            return redirect()->back()->with('error', 'File laporan tidak ditemukan.');
        }
    }

    // Metode yang tidak digunakan dalam resource route tapi ada di skeleton
    public function edit(Laporan $laporan): RedirectResponse
    {
        abort(404);
    }
    public function update(Request $request, Laporan $laporan): RedirectResponse
    {
        abort(404);
    }
    public function destroy(Laporan $laporan): RedirectResponse
    {
        try {
            // Hapus file laporan fisik
            if (Storage::exists(str_replace('/storage', 'public', $laporan->path_file))) {
                Storage::delete(str_replace('/storage', 'public', $laporan->path_file));
            }
            $laporan->delete();
            return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('laporan.index')->with('error', 'Gagal menghapus laporan.');
        }
    }

    // ... (metode index, create, store, show, destroy, download yang sudah ada) ...

    /**
     * Generate PDF for Peminjaman Status Report.
     * Membuat laporan PDF berdasarkan status peminjaman.
     */
    public function generatePeminjamanStatusPdf(Request $request)
    {
        $request->validate([
            'status' => 'nullable|string|in:pending,dipinjam,diajukan_pengembalian,dikembalikan,terlambat,hilang,ditolak',
        ]);

        $query = Peminjaman::with(['user', 'buku']);

        if ($request->filled('status')) {
            $query->where('status_peminjaman', $request->status);
        }

        $peminjamanData = $query->orderBy('created_at', 'desc')->get();
        $reportTitle = 'Laporan Status Peminjaman';
        $filterApplied = $request->status ? 'Status: ' . ucfirst($request->status) : 'Semua Status';

        $pdf = Pdf::loadView('laporan.pdf.peminjaman_status', compact('peminjamanData', 'reportTitle', 'filterApplied'));
        return $pdf->download('laporan-status-peminjaman-' . Carbon::now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Generate PDF for Transaction Date Report.
     * Membuat laporan PDF berdasarkan rentang tanggal transaksi.
     */
    public function generateTransaksiTanggalPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Peminjaman::with(['user', 'buku']);

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->end_date)
                ->orWhereDate('tanggal_pengembalian', '<=', $request->end_date); // Juga cek tgl kembali
        }

        $peminjamanData = $query->orderBy('created_at', 'desc')->get();
        $reportTitle = 'Laporan Transaksi Peminjaman/Pengembalian';
        $filterApplied = ($request->start_date && $request->end_date) ? "Dari: {$request->start_date} - Sampai: {$request->end_date}" : 'Semua Tanggal';

        $pdf = Pdf::loadView('laporan.pdf.transaksi_tanggal', compact('peminjamanData', 'reportTitle', 'filterApplied'));
        return $pdf->download('laporan-transaksi-tanggal-' . Carbon::now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Generate PDF for Denda Report.
     * Membuat laporan PDF denda.
     */
    public function generateDendaPdf(Request $request)
    {
        $request->validate([
            'status_pembayaran' => 'nullable|string|in:lunas,belum_bayar',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Denda::with('peminjaman.user', 'peminjaman.buku');

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $dendaData = $query->orderBy('created_at', 'desc')->get();
        $reportTitle = 'Laporan Denda';
        $filterApplied = '';
        if ($request->status_pembayaran) $filterApplied .= 'Status: ' . ucfirst($request->status_pembayaran) . '. ';
        if ($request->start_date && $request->end_date) $filterApplied .= "Dari: {$request->start_date} - Sampai: {$request->end_date}";
        if (!$filterApplied) $filterApplied = 'Semua Denda';


        $pdf = Pdf::loadView('laporan.pdf.denda', compact('dendaData', 'reportTitle', 'filterApplied'));
        return $pdf->download('laporan-denda-' . Carbon::now()->format('Ymd_His') . '.pdf');
    }
}
