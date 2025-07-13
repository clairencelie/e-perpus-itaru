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
        Schema::create('buku_kategori', function (Blueprint $table) {
            $table->unsignedInteger('id_buku'); // Foreign Key ke Buku
            $table->unsignedInteger('id_kategori'); // Foreign Key ke Kategori

            // Mendefinisikan primary key gabungan
            $table->primary(['id_buku', 'id_kategori']);

            // Mendefinisikan Foreign Keys: onDelete('cascade')
            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_kategori');
    }
};
