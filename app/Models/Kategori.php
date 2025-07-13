<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
    ];

    // Relasi Many-to-Many: Kategori memiliki banyak Buku melalui tabel pivot buku_kategori
    public function buku()
    {
        return $this->belongsToMany(Buku::class, 'buku_kategori', 'id_kategori', 'id_buku');
    }
}
