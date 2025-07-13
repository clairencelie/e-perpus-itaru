<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_peminjaman',
        'tanggal_dibuat',
        'jenis_laporan',
        'nama_file',
        'path_file',
        'rentang_tanggal_awal',
        'rentang_tanggal_akhir',
    ];

    protected $casts = [
        'tanggal_dibuat' => 'date',
        'rentang_tanggal_awal' => 'date',
        'rentang_tanggal_akhir' => 'date',
    ];

    // Relasi: Laporan bisa terkait dengan satu Peminjaman (nullable)
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    // Metode yang ada di Class Diagram
    public function generate()
    {
        // Logika untuk menghasilkan laporan (misalnya PDF, Excel)
        // Ini biasanya akan dipicu dari controller atau job/queue
        return true;
    }

    public function download()
    {
        // Logika untuk mendownload file laporan
        // Biasanya ditangani di controller
        return true;
    }
}
