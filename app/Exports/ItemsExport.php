<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Mengambil data dari database
    */
    public function collection()
    {
        return Item::all();
    }

    /**
     * Membuat Header Kolom di Excel agar rapi
     */
    public function headings(): array
    {
        return [
            'ID Sistem',
            'Nama Barang',
            'Kode Barang',
            'Tahun',
            'Lokasi',
            'Kondisi',
            'Waktu Input',
            'Terakhir Update',
        ];
    }

    /**
     * Memetakan data agar sesuai urutan header
     */
    public function map($item): array
    {
        return [
            $item->id,
            $item->nama_barang,
            $item->kode_barang,
            $item->tahun_barang,
            $item->lokasi_barang,
            $item->kondisi_barang,
            $item->created_at,
            $item->updated_at,
        ];
    }
}
