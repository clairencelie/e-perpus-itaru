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
        Schema::create('buku', function (Blueprint $table) {
            $table->increments('id_buku'); // Primary Key: int
            $table->string('judul'); // string
            $table->integer('tahun_terbit'); // int
            $table->string('ISBN')->unique(); // string, harus unik
            $table->integer('stok_buku'); // int
            $table->text('deskripsi_buku')->nullable(); // text, bisa kosong
            $table->enum('status_ketersediaan', ['tersedia', 'dipinjam', 'hilang'])->default('tersedia'); // enum
            $table->string('file_PDF')->nullable(); // string, bisa kosong
            $table->string('tautan_digital')->nullable(); // string, bisa kosong

            // Foreign Key untuk Penerbit:
            // unsignedInteger karena id_penerbit adalah auto-increment (positif)
            // nullable karena mungkin ada buku tanpa penerbit atau penerbit dihapus
            // onDelete('set null') berarti jika penerbit dihapus, id_penerbit di buku akan jadi null
            $table->unsignedInteger('id_penerbit')->nullable();
            $table->foreign('id_penerbit')->references('id_penerbit')->on('penerbit')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
