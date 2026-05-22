<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'reason',
        'approved_by',
        'disposal_date',
        'status'
    ];

    protected $casts = [
        'disposal_date' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
