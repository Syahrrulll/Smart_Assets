<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'user_id', 'user_name', 'field_name', 'old_value', 'new_value'
    ];

    // Relasi ke User (Siapa yang mengubah)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Item (Barang apa yang diubah)
    // PERBAIKAN: Fungsi ini wajib ada agar 'with(item)' di Controller berjalan
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
