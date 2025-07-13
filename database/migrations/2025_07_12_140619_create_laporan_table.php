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
        Schema::create('laporan', function (Blueprint $table) {
            $table->increments('id_laporan'); // Primary Key: int
            $table->date('tanggal_dibuat'); // date
            $table->enum('jenis_laporan', ['operasional', 'transaksi', 'stok_buku']); // enum
            $table->string('nama_file'); // string
            $table->string('path_file'); // string (path ke file laporan yang dihasilkan)
            $table->date('rentang_tanggal_awal')->nullable(); // date, bisa kosong
            $table->date('rentang_tanggal_akhir')->nullable(); // date, bisa kosong
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
