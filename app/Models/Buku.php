<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'judul',
        'tahun_terbit',
        'cover',
        'ISBN',
        'stok_buku',
        'deskripsi_buku',
        'status_ketersediaan',
        'file_PDF',
        'tautan_digital',
        'id_penerbit', // Pastikan foreign key juga ada di fillable
    ];

    // Relasi: Buku dimiliki oleh satu Penerbit
    public function penerbit()
    {
        // belongsTo(Nama_Model_Pemilik::class, 'foreign_key_di_tabel_ini', 'owner_key_di_tabel_pemilik')
        return $this->belongsTo(Penerbit::class, 'id_penerbit', 'id_penerbit');
    }

    // Relasi Many-to-Many: Buku memiliki banyak Pengarang melalui tabel pivot buku_pengarang
    public function pengarangs()
    {
        // belongsToMany(Nama_Model_Terkait::class, 'nama_tabel_pivot', 'foreign_key_tabel_ini_di_pivot', 'foreign_key_tabel_terkait_di_pivot')
        return $this->belongsToMany(Pengarang::class, 'buku_pengarang', 'id_buku', 'id_pengarang');
    }

    // Relasi Many-to-Many: Buku memiliki banyak Kategori melalui tabel pivot buku_kategori
    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'buku_kategori', 'id_buku', 'id_kategori');
    }

    // Relasi: Buku memiliki banyak Peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku', 'id_buku');
    }
}
