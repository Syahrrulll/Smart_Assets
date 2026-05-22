<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'nup',
        'tahun_barang',
        'lokasi_barang',
        'kondisi_barang',
        'foto_barang',
        'umur_ekonomis',
        'tahun_berakhir', 
    ];

    // Relasi ke History
    public function histories()
    {
        return $this->hasMany(ItemHistory::class)->latest();
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class)->latest();
    }

    public function maintenanceTickets()
    {
        return $this->hasMany(MaintenanceTicket::class)->latest();
    }

    public function disposals()
    {
        return $this->hasMany(Disposal::class)->latest();
    }

    public function relocations()
    {
        return $this->hasMany(Relocation::class)->latest();
    }
}
