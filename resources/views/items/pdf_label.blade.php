<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 10px;
            size: A4 landscape;
        }
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            font-size: 0; /* Menghilangkan spasi antar elemen inline-block */
        }

        /* Gunakan inline-block, bukan float, agar DOMPDF lebih stabil menangani ribuan data */
        .label-box {
            width: 9.8%; /* 10 Kolom */
            display: inline-block;
            vertical-align: top;
            border: 1px dashed #999;
            text-align: center;
            padding: 5px 0;
            margin-right: 0.1%;
            margin-bottom: 5px;
            height: 110px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            margin-bottom: 2px;
            padding: 0 2px;
            line-height: 10px;
        }

        .qr-wrapper {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            position: relative;
            display: block;
        }

        /* Pastikan SVG pas */
        .qr-wrapper svg {
            width: 100%;
            height: 100%;
        }

        .code {
            font-size: 8px;
            background-color: #000;
            color: #fff;
            display: inline-block;
            padding: 1px 3px;
            margin-top: 2px;
            border-radius: 2px;
            font-family: monospace;
            line-height: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        @foreach($items as $item)
            <div class="label-box">
                <div class="title">{{ substr($item->nama_barang, 0, 15) }}</div>

                <div class="qr-wrapper">
                    <?php
                        // Generate SVG dengan koreksi kesalahan 'L' (Low) agar file lebih ringan
                        $qrCode = QrCode::size(100)->format('svg')->errorCorrection('L')->generate(route('items.edit', $item->id));

                        // KRUSIAL: Hapus Deklarasi XML (<?xml...>) agar DOMPDF tidak crash saat looping ribuan kali
                        $qrCodeClean = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $qrCode);
                    ?>
                    {!! $qrCodeClean !!}
                </div>

                <div class="code">{{ $item->kode_barang }}</div>
            </div>
        @endforeach
    </div>
</body>
</html>
