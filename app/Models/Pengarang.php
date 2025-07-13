<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengarang extends Model
{
    use HasFactory;

    protected $table = 'pengarang';
    protected $primaryKey = 'id_pengarang';

    protected $fillable = [
        'nama_pengarang',
    ];

    // Relasi Many-to-Many: Pengarang memiliki banyak Buku melalui tabel pivot buku_pengarang
    public function buku()
    {
        return $this->belongsToMany(Buku::class, 'buku_pengarang', 'id_pengarang', 'id_buku');
    }
}
