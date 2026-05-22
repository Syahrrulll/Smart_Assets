<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Import Barang Excel/CSV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <!-- Tombol Kembali -->
            <a href="{{ route('items.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-file-csv me-2"></i>Import Data Excel / CSV</h4>
                </div>
                <div class="card-body p-4">

                    <div class="alert alert-warning border-start border-warning border-4">
                        <strong><i class="fas fa-exclamation-triangle me-1"></i> Penting:</strong><br>
                        <ul>
                            <li>Kolom <strong>nama_barang</strong> dan <strong>kode_barang</strong> WAJIB diisi.</li>
                            <li>Kolom lain (tahun, lokasi, kondisi) boleh dikosongkan.</li>
                            <li>Foto barang tidak bisa diimport via Excel (harus upload manual via Edit).</li>
                        </ul>
                    </div>

                    <!-- Tombol Download Template -->
                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded border">
                        <div>
                            <h6 class="fw-bold mb-1">Belum punya formatnya?</h6>
                            <small class="text-muted">Download template ini agar kolomnya sesuai.</small>
                        </div>
                        <button onclick="downloadTemplate()" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download me-1"></i> Download Template
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="importForm" action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih File (.xlsx / .xls / .csv)</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required>
                            <div class="form-text">Pastikan file sudah sesuai dengan template di atas.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success fw-bold py-2">
                                <i class="fas fa-upload me-2"></i>Upload & Proses Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 z-3 d-flex flex-column justify-content-center align-items-center" style="z-index: 1050;">
    <div class="spinner-border text-success" style="width: 4rem; height: 4rem;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <h4 class="text-white mt-3 fw-bold">Memproses Data...</h4>
    <p class="text-white-50">Mohon jangan tutup halaman ini, memproses data besar membutuhkan waktu.</p>
</div>

<script>
    // Tampilkan animasi loading saat form disubmit
    document.getElementById('importForm').addEventListener('submit', function() {
        // Hanya tampilkan jika form valid (ada file yang dipilih)
        if(this.checkValidity()) {
            const overlay = document.getElementById('loadingOverlay');
            overlay.classList.remove('d-none');
            overlay.classList.add('d-flex');
        }
    });

    // Fungsi untuk membuat file CSV template secara otomatis
    function downloadTemplate() {
        // UPDATE: Menghapus 'foto_barang' agar tidak membingungkan
        const headers = [
            "nama_barang",
            "kode_barang",
            "tahun_barang",
            "lokasi_barang",
            "kondisi_barang"
        ];

        // Contoh data dummy
        const example = [
            "Laptop Baru",
            "INV-NEW-01",
            "2024",
            "Gudang A",
            "Baik"
        ];

        // Tambahkan BOM (\uFEFF) agar Excel membaca UTF-8 dengan benar (mencegah kolom menyatu)
        const csvContent = "data:text/csv;charset=utf-8,\uFEFF"
            + headers.join(",") + "\n"
            + example.join(",");

        // Membuat link download virtual
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "template_inventaris.csv");
        document.body.appendChild(link);

        link.click();
        document.body.removeChild(link);
    }
</script>

</body>
</html>
