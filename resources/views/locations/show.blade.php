<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Ruangan: {{ $lokasi_asli }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .item-card { transition: transform 0.2s; }
        .item-card:hover { transform: translateY(-2px); }

        .card-img-top-wrapper {
            height: 200px;
            overflow: hidden;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .card-img-top-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <!-- Header Ruangan -->
    <div class="card border-0 shadow-sm bg-primary text-white mb-4 rounded-4 overflow-hidden">
        <div class="card-body p-4 text-center">
            <i class="fas fa-door-open fa-3x mb-3 opacity-50"></i>
            <h2 class="fw-bold mb-1">{{ $lokasi_asli }}</h2>
            <p class="mb-0 opacity-75">Daftar Aset Barang Per Ruangan</p>
        </div>
        <div class="card-footer bg-primary bg-gradient border-0 text-center py-2">
            <span class="badge bg-white text-primary rounded-pill px-3">Total: {{ $items->count() }} Barang</span>
        </div>
    </div>

    <!-- Alert jika kosong -->
    @if($items->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Ruangan ini kosong.</h4>
            <p class="text-secondary">Tidak ada barang yang tercatat di lokasi ini.</p>
        </div>
    @else
        <!-- Daftar Barang (Grid View) -->
        <div class="row g-3">
            @foreach($items as $item)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 item-card overflow-hidden">

                    <!-- AREA FOTO BARANG -->
                    <div class="card-img-top-wrapper border-bottom position-relative">
                        @if($item->foto_barang)
                            <img src="{{ asset($item->foto_barang) }}" alt="{{ $item->nama_barang }}">
                        @else
                            <i class="fas fa-image fa-3x text-muted opacity-25"></i>
                        @endif

                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-dark bg-opacity-75 font-monospace">{{ $item->kode_barang }}</span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <h5 class="fw-bold text-dark mb-1 text-truncate" title="{{ $item->nama_barang }}">
                                {{ $item->nama_barang }}
                            </h5>
                        </div>

                        <div class="mb-3 small">
                            <div class="d-flex justify-content-between text-muted mb-1">
                                <span><i class="far fa-calendar-alt me-1"></i> Tahun</span>
                                <span>{{ $item->tahun_barang ?? '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span><i class="far fa-clock me-1"></i> Update</span>
                                <span>{{ $item->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <!-- Status Kondisi -->
                            @php
                                $badgeClass = match($item->kondisi_barang) {
                                    'Baik' => 'bg-success',
                                    'Rusak Ringan' => 'bg-warning text-dark',
                                    'Rusak Berat' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <div class="d-grid">
                                <span class="badge {{ $badgeClass }} py-2">
                                    {{ $item->kondisi_barang ?? 'Belum Dicek' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

</body>
</html>
