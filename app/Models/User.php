<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'nama',
        'email',
        'role', // If you're explicitly setting it
        'kampus',
        'alamat',
        'no_hp',
        'foto',
    ];


    protected $primaryKey = 'id_user';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: User memiliki banyak Peminjaman
    public function peminjaman()
    {
        // hasMany(Nama_Model_Terkait::class, 'foreign_key_di_tabel_terkait', 'local_key_di_tabel_ini')
        return $this->hasMany(Peminjaman::class, 'id_user', 'id_user');
    }

    // Metode yang ada di Class Diagram (implementasi logika bisa di Controller/Service)
    public function getDetail()
    {
        // Contoh sederhana, bisa lebih kompleks
        return $this;
    }

    public function editProfile(array $data)
    {
        return $this->update($data);
    }
}
