<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Hapus history jika barang dihapus
            $table->foreignId('user_id')->nullable()->constrained(); // Siapa yang mengubah
            $table->string('user_name')->nullable(); // Simpan nama user (jaga-jaga jika user dihapus)
            $table->string('field_name'); // Kolom apa yang berubah (Misal: Kondisi, Lokasi)
            $table->text('old_value')->nullable(); // Nilai Lama
            $table->text('new_value')->nullable(); // Nilai Baru
            $table->timestamps(); // Tanggal perubahan (created_at)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_histories');
    }
};
