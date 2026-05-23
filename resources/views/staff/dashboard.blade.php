@extends('layouts.app')

@section('title', 'Dashboard Staf | Smart Asset Management')
@section('header_title', 'Dashboard Staf Gudang')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-indigo-500/30 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10">
            <i class="fas fa-boxes text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold font-outfit mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-indigo-100 text-sm md:text-base">Selamat datang di panel kerja staf gudang. Apa yang ingin Anda lakukan hari ini?</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Scan QR Card -->
        <a href="{{ route('scanner') }}" class="group bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all flex flex-col items-center justify-center text-center gap-4 cursor-pointer">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                <i class="fas fa-qrcode"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-lg mb-1">Mulai Scan Aset</h3>
                <p class="text-xs text-slate-500">Pindai QR Code pada fisik barang untuk memperbarui kondisi atau lokasi.</p>
            </div>
        </a>

        <!-- Report Issue Card -->
        <a href="{{ route('reports.create') }}" class="group bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-rose-200 transition-all flex flex-col items-center justify-center text-center gap-4 cursor-pointer">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-lg mb-1">Laporkan Temuan</h3>
                <p class="text-xs text-slate-500">Laporkan barang hilang, rusak parah, atau anomali lainnya ke Admin.</p>
            </div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800"><i class="fas fa-history text-indigo-500 me-2"></i>Aktivitas Terakhir Anda</h3>
        </div>
        <div class="p-5">
            <p class="text-sm text-slate-500 text-center py-4">Sistem sedang memuat riwayat...</p>
            <!-- Nanti bisa diisi dengan data ItemHistory khusus user ini -->
        </div>
    </div>

</div>
@endsection
