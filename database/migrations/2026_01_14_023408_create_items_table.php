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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Atribut Wajib
            $table->string('nama_barang');
            $table->string('kode_barang')->unique();

            // Atribut Tambahan (Urutan sesuai keinginan Anda)
            // HAPUS 'after()' di sini karena ini create table
            $table->integer('nup')->nullable();

            $table->string('foto_barang')->nullable();
            $table->integer('tahun_barang')->nullable();
            $table->string('lokasi_barang')->nullable();
            $table->string('kondisi_barang')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
