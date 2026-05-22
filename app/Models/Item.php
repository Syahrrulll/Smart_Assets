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
        'harga_beli',
        'maintenance_interval_months',
        'last_maintenance_date',
    ];

    public function getNilaiPenyusutanPerTahunAttribute()
    {
        if (!$this->harga_beli || !$this->umur_ekonomis || $this->umur_ekonomis <= 0) {
            return 0;
        }
        return $this->harga_beli / $this->umur_ekonomis;
    }

    public function getNilaiBukuAttribute()
    {
        if (!$this->harga_beli) return 0;
        if (!$this->umur_ekonomis || !$this->tahun_barang) return $this->harga_beli;

        $currentYear = (int) date('Y');
        $umurTerpakai = $currentYear - $this->tahun_barang;
        
        if ($umurTerpakai < 0) $umurTerpakai = 0;
        if ($umurTerpakai > $this->umur_ekonomis) $umurTerpakai = $this->umur_ekonomis;

        $akumulasiPenyusutan = $this->nilai_penyusutan_per_tahun * $umurTerpakai;
        return max(0, $this->harga_beli - $akumulasiPenyusutan);
    }

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
