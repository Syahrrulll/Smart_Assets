@extends('layouts.app')

@section('title', 'Scanner Aset | Smart Asset Management')
@section('header_title', 'Scanner QR Code')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold font-outfit text-slate-800 mb-2">Arahkan Kamera ke QR Code</h2>
            <p class="text-slate-500 text-sm">Pastikan QR Code berada di dalam kotak area pemindaian. Sistem akan memprosesnya secara otomatis.</p>
        </div>

        <!-- Scanner Container -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 overflow-hidden relative mb-4">
            <div id="qr-reader" class="w-full"></div>
            
            <!-- Loading Overlay (shows briefly while camera starts) -->
            <div id="scanner-loading" class="absolute inset-0 bg-slate-50 flex flex-col items-center justify-center z-10 transition-opacity duration-300">
                <i class="fas fa-circle-notch fa-spin text-3xl text-indigo-500 mb-3"></i>
                <p class="text-slate-600 font-medium">Memulai kamera...</p>
            </div>
        </div>

        <!-- Opsi Manual Upload / Kamera Asli (Selalu Muncul) -->
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5 text-center">
            <h3 class="font-bold text-indigo-800 mb-2">Kamera Tidak Merespons?</h3>
            <p class="text-indigo-600 text-sm mb-4">Gunakan tombol di bawah untuk memotret stiker QR langsung dengan kamera asli HP Anda.</p>
            
            <label class="cursor-pointer inline-flex bg-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-indigo-700 transition items-center gap-2 shadow-sm shadow-indigo-200">
                <i class="fas fa-camera"></i> Buka Kamera / Galeri
                <input type="file" id="qr-input-file" accept="image/*" capture="environment" class="hidden">
            </label>
        </div>

        <div class="mt-6 flex justify-center">
            <a href="{{ url()->previous() }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-xl hover:bg-slate-200 transition-colors">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- HTML5-QRCode Library -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let isScanned = false;
        
        // Listener untuk file upload fallback (selalu aktif)
        document.getElementById('qr-input-file').addEventListener('change', e => {
            if (e.target.files.length == 0) return;
            
            const imageFile = e.target.files[0];
            const html5QrCodeFile = new Html5Qrcode("qr-reader");
            
            // Ubah loading text
            document.getElementById('scanner-loading').style.display = 'flex';
            document.getElementById('scanner-loading').style.opacity = '1';
            document.getElementById('scanner-loading').innerHTML = '<i class="fas fa-spinner fa-spin text-3xl text-indigo-500 mb-3"></i><p class="text-slate-600 font-medium">Menganalisis foto...</p>';

            html5QrCodeFile.scanFile(imageFile, true)
                .then(decodedText => {
                    qrCodeSuccessCallback(decodedText, null);
                })
                .catch(err => {
                    document.getElementById('scanner-loading').style.display = 'none';
                    alert("QR Code tidak ditemukan pada gambar tersebut. Pastikan foto terang dan QR Code terlihat jelas.");
                });
        });

        const html5QrCode = new Html5Qrcode("qr-reader");

        // Ketika QR Code berhasil dibaca
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if(isScanned) return; 
            
            if(decodedText.includes('/items/') && decodedText.includes('/edit')) {
                isScanned = true;
                document.getElementById('qr-reader').style.border = "4px solid #10b981"; 
                
                html5QrCode.stop().then((ignore) => {
                    const url = new URL(decodedText);
                    url.searchParams.set('scanned_from_qr', '1');
                    window.location.href = url.toString();
                }).catch((err) => {
                    // Paksa redirect jika gagal stop
                    const url = new URL(decodedText);
                    url.searchParams.set('scanned_from_qr', '1');
                    window.location.href = url.toString();
                });
            } else {
                alert("QR Code tidak valid! Pastikan Anda memindai stiker Smart Asset Management.");
                isScanned = true;
                setTimeout(() => { isScanned = false; }, 2000);
            }
        };

        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        try {
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .then(() => {
                    document.getElementById('scanner-loading').style.opacity = '0';
                    setTimeout(() => { document.getElementById('scanner-loading').style.display = 'none'; }, 300);
                })
                .catch((err) => {
                    document.getElementById('scanner-loading').innerHTML = `
                        <i class="fas fa-video-slash text-4xl text-rose-400 mb-3"></i>
                        <p class="text-slate-700 font-bold">Kamera Web Diblokir</p>
                        <p class="text-slate-500 text-xs text-center px-4 mt-1">Gunakan tombol kamera biru di bawah.</p>
                    `;
                });
        } catch (e) {
            document.getElementById('scanner-loading').innerHTML = `
                <i class="fas fa-video-slash text-4xl text-rose-400 mb-3"></i>
                <p class="text-slate-700 font-bold">Kamera Web Diblokir</p>
                <p class="text-slate-500 text-xs text-center px-4 mt-1">Gunakan tombol kamera biru di bawah.</p>
            `;
        }
    });
</script>
@endsection
