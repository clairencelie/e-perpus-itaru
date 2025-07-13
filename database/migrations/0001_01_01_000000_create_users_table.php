<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Mengubah primary key dari 'id' menjadi 'id_user' sesuai Class Diagram
            // Menggunakan increments() untuk integer primary key yang auto-increment
            $table->increments('id_user');

            // Menambahkan 'username' sebagai string unik
            $table->string('username')->unique();

            // 'password' sudah ada, pastikan tipe datanya string
            $table->string('password');

            // Mengubah 'name' bawaan Laravel menjadi 'nama' sesuai Class Diagram
            $table->string('nama');

            // 'email' sudah ada, pastikan tipe datanya string dan unik
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Tetap pertahankan untuk fitur verifikasi email

            // Menambahkan 'role' sebagai enum
            // Default 'anggota' karena ini adalah peran dasar bagi pengguna yang mendaftar
            $table->enum('role', ['anggota', 'staff', 'kepala perpustakaan'])->default('anggota');

            // Menambahkan 'kampus' sebagai string, nullable karena mungkin tidak semua user punya atau wajib diisi
            $table->string('kampus')->nullable();

            // Menambahkan 'alamat' sebagai string, nullable
            $table->string('alamat')->nullable();

            // Menambahkan 'no_hp' sebagai string, nullable
            $table->string('no_hp')->nullable();

            // Menambahkan 'foto' sebagai string (untuk path file), nullable
            $table->string('foto')->nullable();

            // 'remember_token' sudah ada, pertahankan untuk fitur "ingat saya"
            $table->rememberToken();

            // 'created_at' dan 'updated_at' (kolom timestamp otomatis Laravel)
            $table->timestamps();
        });

        // --- BAGIAN YANG PERLU ANDA TAMBAHKAN KEMBALI ---
        // Definisi tabel password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Definisi tabel sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Saat rollback migrasi, tabel users akan dihapus
        Schema::dropIfExists('users');
    }
};
