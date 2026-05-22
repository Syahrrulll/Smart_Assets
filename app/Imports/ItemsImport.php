<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Str;

class ItemsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $latestNup;

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function __construct()
    {
        // Ambil NUP terakhir di sistem
        $this->latestNup = Item::max('nup') ?? 0;
    }

    public function model(array $row)
    {
        // Abaikan baris kosong
        if (empty(array_filter($row))) {
            return null;
        }

        // ==========================================
        // 1. MAPPING KOLOM WAJIB
        // ==========================================
        $nama = $row['nama_barang'] ?? $row['nama'] ?? null;
        $kode_asli = $row['kode_barang'] ?? $row['kode'] ?? null;
        $nup_excel = $row['nup'] ?? null;

        if (!$nama || !$kode_asli) {
            return null;
        }

        // Buat Kode Unik
        $kode_unik = $kode_asli;
        if (!empty($nup_excel)) {
            $kode_unik = $kode_asli . '-' . $nup_excel;
        } else {
            $kode_unik = $kode_asli . '-' . Str::random(5);
        }

        // ==========================================
        // 2. EKSTRAKSI TAHUN DASAR (PRIORITAS: PEMBUKUAN -> PEROLEHAN -> TAHUN INI)
        // ==========================================
        $tahun = null;

        // Prioritas 1: Ambil dari Tanggal Buku Pertama (dari data CSV/Excel Anda)
        if (!empty($row['tanggal_buku_pertama'])) {
            $tahun = substr($row['tanggal_buku_pertama'], 0, 4); // Ambil 2021 dari 2021-12-31
        }
        elseif (!empty($row['tanggal_pembukuan'])) {
            $tahun = substr($row['tanggal_pembukuan'], 0, 4);
        }
        // Prioritas 2: Jika pembukuan kosong, gunakan Tanggal Perolehan
        elseif (!empty($row['tanggal_perolehan'])) {
            $tahun = substr($row['tanggal_perolehan'], 0, 4);
        }
        elseif (!empty($row['tahun_perolehan'])) {
            $tahun = $row['tahun_perolehan'];
        }
        elseif (!empty($row['tahun_barang'])) {
            $tahun = $row['tahun_barang'];
        }
        // Prioritas 3: Jika kosong semua, asumsikan baru dimasukkan tahun ini
        else {
            $tahun = date('Y');
        }

        // ==========================================
        // 3. LOGIKA UMUR ASET & PREDIKSI
        // ==========================================
        $umur_ekonomis = null;

        // Ambil nilai umur aset langsung dari Excel (termasuk jika nilainya 0)
        if (isset($row['umur_aset']) && $row['umur_aset'] !== '') {
            $umur_ekonomis = (int)$row['umur_aset'];
        } else {
            // Jika kolom umur aset di Excel kosong, panggil fungsi tebakan pintar
            $umur_ekonomis = $this->predictUmur($nama);
        }

        // Hitung tahun berakhirnya berdasarkan Tahun Dasar yang baru
        $tahun_berakhir = null;
        if ($tahun !== null && $umur_ekonomis !== null) {
            $tahun_berakhir = (int)$tahun + $umur_ekonomis;
        }

        // Data Opsional lainnya
        $lokasi = $row['lokasi_ruang'] ?? $row['lokasi_barang'] ?? $row['lokasi'] ?? null;
        $kondisi = $row['kondisi'] ?? $row['kondisi_barang'] ?? null;

        // ==========================================
        // 4. PROSES SIMPAN / UPDATE KE DATABASE
        // ==========================================
        $item = Item::where('kode_barang', $kode_unik)->first();

        if ($item) {
            // UPDATE DATA LAMA
            $item->update([
                'nama_barang'    => $nama,
                'tahun_barang'   => $tahun, // Update dengan tahun pembukuan terbaru
                'umur_ekonomis'  => $umur_ekonomis,
                'tahun_berakhir' => $tahun_berakhir,
                'lokasi_barang'  => $lokasi ?? $item->lokasi_barang,
                'kondisi_barang' => $kondisi ?? $item->kondisi_barang,
            ]);
            return null; // Jangan di-return agar tidak ikut terkena proses INSERT oleh WithBatchInserts

        } else {
            // BUAT BARANG BARU
            $this->latestNup++;

            return new Item([
                'nama_barang'    => $nama,
                'kode_barang'    => $kode_unik,
                'nup'            => $nup_excel ? (int)$nup_excel : $this->latestNup,
                'tahun_barang'   => $tahun, // Simpan tahun pembukuan
                'umur_ekonomis'  => $umur_ekonomis,
                'tahun_berakhir' => $tahun_berakhir,
                'lokasi_barang'  => $lokasi,
                'kondisi_barang' => $kondisi,
                'foto_barang'    => null,
            ]);
        }
    }

    /**
     * Helper Private: Logika Prediksi Umur (Sistem Pintar Cadangan)
     */
    private function predictUmur($namaBarang)
    {
        $nama = strtolower($namaBarang);

        // Aturan prediksi yang realistis
        if (\Illuminate\Support\Str::contains($nama, ['laptop', 'komputer', 'pc', 'printer', 'scanner'])) return 4;
        if (\Illuminate\Support\Str::contains($nama, ['mobil', 'motor', 'kendaraan'])) return 7;
        if (\Illuminate\Support\Str::contains($nama, ['meja', 'kursi', 'lemari', 'rak'])) return 10;
        if (\Illuminate\Support\Str::contains($nama, ['tanah', 'lahan'])) return 999;
        if (\Illuminate\Support\Str::contains($nama, ['gedung', 'bangunan'])) return 50;
        if (\Illuminate\Support\Str::contains($nama, ['buku', 'monografi'])) return 5;

        return 5; // Default umur jika semua gagal ditebak
    }
}
