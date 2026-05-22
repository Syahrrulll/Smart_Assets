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
        Schema::table('items', function (Blueprint $table) {
            $table->index('lokasi_barang');
            $table->index('kondisi_barang');
            $table->index('tahun_barang');
            $table->index('nup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex(['lokasi_barang']);
            $table->dropIndex(['kondisi_barang']);
            $table->dropIndex(['tahun_barang']);
            $table->dropIndex(['nup']);
        });
    }
};
