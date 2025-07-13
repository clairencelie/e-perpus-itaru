<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Opsi 2: Menggunakan SQL Mentah (Paling Langsung)
        DB::statement("ALTER TABLE peminjaman CHANGE COLUMN status_peminjaman status_peminjaman ENUM('pending', 'dipinjam', 'dikembalikan', 'terlambat', 'hilang', 'ditolak') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman CHANGE COLUMN status_peminjaman status_peminjaman ENUM('dipinjam', 'dikembalikan', 'terlambat', 'hilang') NOT NULL DEFAULT 'dipinjam'");
    }
};
