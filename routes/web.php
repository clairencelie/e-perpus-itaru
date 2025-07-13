<?php
// TODO: TES DENDA
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\PengarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
// use App\Http\Controllers\UserController; // Import jika Anda membuat kontroler khusus untuk manajemen user oleh admin

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// --- GRUP RUTE UNTUK SEMUA USER TER-OTENTIKASI (Anggota, Staff, Kepala Perpustakaan) ---
Route::middleware('auth')->group(function () {
    // Rute Profil Pengguna (bisa diakses semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Anggota: Melihat Katalog Buku, Mengajukan Peminjaman, Melihat Riwayat Peminjaman, dll.
    Route::get('/katalog-buku', [BukuController::class, 'indexPublic'])->name('katalog.index'); // Katalog buku untuk anggota
    Route::get('/katalog-buku/{buku}', [BukuController::class, 'showPublic'])->name('katalog.show'); // Detail buku katalog

    // Rute untuk anggota langsung mengajukan peminjaman (POST request dari tombol di katalog)
    Route::post('/peminjaman/request-borrow', [PeminjamanController::class, 'requestBorrow'])->name('peminjaman.request_borrow');

    // Anggota bisa melihat riwayat peminjamannya sendiri
    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'myHistory'])->name('peminjaman.my_history');

    // Anggota bisa mengajukan pengembalian (Staff yang akan memproses fisik)
    Route::get('/peminjaman/kembalikan/{peminjaman}', [PeminjamanController::class, 'requestReturn'])->name('peminjaman.request_return');

    // Anggota bisa melihat denda mereka sendiri
    Route::get('/denda/saya', [DendaController::class, 'myDenda'])->name('denda.my_denda');

    Route::post('/peminjaman/{id_peminjaman}/request-return', [PeminjamanController::class, 'requestReturn'])->name('peminjaman.request_return');
});


// --- GRUP RUTE UNTUK STAFF dan KEPALA PERPUSTAKAAN ---
// Middleware 'role:staff' akan memastikan hanya user dengan role 'staff' atau 'kepala perpustakaan' yang bisa mengakses
Route::middleware(['auth', 'role:staff'])->group(function () {
    // Modul Manajemen Data Master (Penerbit, Buku, Pengarang, Kategori)
    Route::resource('penerbit', PenerbitController::class);
    Route::resource('buku', BukuController::class);
    Route::resource('pengarang', PengarangController::class);
    Route::resource('kategori', KategoriController::class);

    // Modul Manajemen Peminjaman oleh Staff (approve, reject, process return)
    // Kita gunakan 'except' untuk menghindari pembuatan rute 'create', 'store', 'edit', 'update', 'destroy'
    // yang tidak relevan untuk staff memproses peminjaman dari sisi CRUD standar.
    // Metode-metode ini sudah digantikan oleh alur 'request-borrow' dari anggota dan proses oleh staff.
    Route::resource('peminjaman', PeminjamanController::class)->except(['create', 'store', 'destroy']);
    // Rute spesifik untuk aksi staff pada peminjaman
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show'); // Staff melihat detail peminjaman
    Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{peminjaman}/process-return', [PeminjamanController::class, 'update'])->name('peminjaman.process_return'); // Staff memproses pengembalian fisik

    // Modul Manajemen Denda oleh Staff (CRUD denda)
    Route::resource('denda', DendaController::class);

    // Contoh: Manajemen Anggota (Staff bisa melihat/edit anggota)
    // Jika Anda membuat UserController khusus untuk admin/staff, tambahkan rutenya di sini
    // Route::get('/admin/anggota', [UserController::class, 'indexAnggota'])->name('admin.anggota.index');
    // Route::get('/admin/anggota/{user}/edit', [UserController::class, 'editAnggota'])->name('admin.anggota.edit');
    // Route::put('/admin/anggota/{user}', [UserController::class, 'updateAnggota'])->name('admin.anggota.update');
});

// --- GRUP RUTE KHUSUS UNTUK KEPALA PERPUSTAKAAN ---
// Hanya user dengan role 'kepala perpustakaan' yang bisa mengakses ini
Route::middleware(['auth', 'role:kepala perpustakaan'])->group(function () {
    Route::resource('laporan', LaporanController::class)->only(['index', 'create', 'store', 'show', 'destroy']); // Batasi resource method yang digunakan
    Route::get('/laporan/{laporan}/download', [LaporanController::class, 'download'])->name('laporan.download'); // Untuk download laporan yang sudah tersimpan

    // --- Rute Baru untuk Generate Laporan Spesifik ke PDF ---
    Route::get('/laporan/generate/peminjaman-status', [LaporanController::class, 'generatePeminjamanStatusPdf'])->name('laporan.generate.peminjaman_status_pdf');
    Route::get('/laporan/generate/transaksi-tanggal', [LaporanController::class, 'generateTransaksiTanggalPdf'])->name('laporan.generate.transaksi_tanggal_pdf');
    Route::get('/laporan/generate/denda', [LaporanController::class, 'generateDendaPdf'])->name('laporan.generate.denda_pdf');
});

require __DIR__ . '/auth.php';
