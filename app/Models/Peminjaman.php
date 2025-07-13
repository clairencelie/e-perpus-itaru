<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_user',
        'id_buku',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_pengembalian',
        'status_peminjaman',
        'keterangan',
    ];

    // Cast kolom tanggal ke objek Carbon (date)
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_pengembalian' => 'date',
    ];

    // Relasi: Peminjaman dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Peminjaman terkait dengan satu Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    // Relasi: Peminjaman memiliki banyak Denda
    public function denda()
    {
        return $this->hasMany(Denda::class, 'id_peminjaman', 'id_peminjaman');
    }

    // Metode yang ada di Class Diagram
    public function approve()
    {
        $this->status_peminjaman = 'dipinjam';
        return $this->save();
    }

    public function reject()
    {
        $this->status_peminjaman = 'ditolak'; // Pastikan 'ditolak' ada di enum migrasi
        return $this->save();
    }
}
