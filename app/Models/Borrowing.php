<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'borrower_name',
        'borrowed_at',
        'expected_return_at',
        'returned_at',
        'status'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'expected_return_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
