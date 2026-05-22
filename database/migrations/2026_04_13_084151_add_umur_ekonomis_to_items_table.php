<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('umur_ekonomis')->nullable()->after('tahun_barang'); // Prediksi masa pakai (dalam tahun)
            $table->integer('tahun_berakhir')->nullable()->after('umur_ekonomis'); // Tahun barang dianggap expired
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['umur_ekonomis', 'tahun_berakhir']);
        });
    }
};
