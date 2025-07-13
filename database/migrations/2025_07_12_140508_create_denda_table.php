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
        Schema::create('denda', function (Blueprint $table) {
            $table->increments('id_denda'); // Primary Key: int
            $table->unsignedInteger('id_peminjaman'); // Foreign Key ke Peminjaman
            $table->decimal('nominal_denda', 10, 2); // decimal (total 10 digit, 2 di belakang koma)
            $table->date('tanggal_bayar')->nullable(); // date, bisa kosong
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas'])->default('belum_bayar'); // enum
            $table->boolean('is_terlambat')->default(false); // boolean
            $table->boolean('is_rusak')->default(false); // boolean
            $table->timestamps();

            // Foreign Key: onDelete('cascade') berarti jika peminjaman dihapus, denda terkait juga ikut terhapus
            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
