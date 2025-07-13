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
        Schema::create('buku_pengarang', function (Blueprint $table) {
            $table->unsignedInteger('id_buku'); // Foreign Key ke Buku
            $table->unsignedInteger('id_pengarang'); // Foreign Key ke Pengarang

            // Mendefinisikan primary key gabungan dari kedua foreign key
            // Ini memastikan kombinasi id_buku dan id_pengarang selalu unik
            $table->primary(['id_buku', 'id_pengarang']);

            // Mendefinisikan Foreign Keys: onDelete('cascade') berarti jika buku/pengarang dihapus, entri di tabel pivot juga ikut terhapus
            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('cascade');
            $table->foreign('id_pengarang')->references('id_pengarang')->on('pengarang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_pengarang');
    }
};
