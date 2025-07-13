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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->increments('id_peminjaman'); // Primary Key: int
            $table->unsignedInteger('id_user'); // Foreign Key ke Users
            $table->unsignedInteger('id_buku'); // Foreign Key ke Buku
            $table->date('tanggal_pinjam'); // date
            $table->date('tanggal_jatuh_tempo'); // date
            $table->date('tanggal_pengembalian')->nullable(); // date, bisa kosong
            $table->enum('status_peminjaman', ['dipinjam', 'dikembalikan', 'terlambat', 'hilang', 'ditolak'])->default('dipinjam'); // enum
            $table->text('keterangan')->nullable(); // text, bisa kosong
            $table->timestamps();

            // Foreign Keys: onDelete('cascade') berarti jika user/buku dihapus, peminjaman terkait juga ikut terhapus
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
