<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';
    protected $primaryKey = 'id_denda';

    protected $fillable = [
        'id_peminjaman',
        'nominal_denda',
        'tanggal_bayar',
        'status_pembayaran',
        'is_terlambat',
        'is_rusak',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'is_terlambat' => 'boolean',
        'is_rusak' => 'boolean',
    ];

    // Relasi: Denda dimiliki oleh satu Peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    // Metode yang ada di Class Diagram
    public function hitungDenda()
    {
        // Logika perhitungan denda. Biasanya melibatkan tanggal_jatuh_tempo dan tanggal_pengembalian dari peminjaman
        // return ...;
    }

    public function catatPembayaran(float $jumlah_bayar)
    {
        // Logika untuk mencatat pembayaran
        if ($jumlah_bayar >= $this->nominal_denda) {
            $this->status_pembayaran = 'lunas';
            $this->tanggal_bayar = now(); // Mengisi tanggal pembayaran otomatis
        }
        return $this->save();
    }
}
