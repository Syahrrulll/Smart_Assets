<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'reported_by',
        'issue_description',
        'status',
        'cost',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
