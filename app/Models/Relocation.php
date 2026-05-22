<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relocation extends Model
{
    protected $fillable = [
        'item_id',
        'from_location',
        'to_location',
        'requested_by',
        'approved_by',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
