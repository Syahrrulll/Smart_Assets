@extends('layouts.app')

@section('title', 'Buat Laporan | Smart Asset Management')
@section('header_title', 'Laporkan Temuan Aset')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center">
        <a href="{{ Auth::user()->role == 'admin' ? route('items.index') : route('staff.dashboard') }}" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition-all mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold font-outfit text-slate-800">Buat Laporan Baru</h2>
            <p class="text-slate-500 text-sm">Laporkan kehilangan, kerusakan parah, atau anomali aset.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start">
            <i class="fas fa-check-circle mt-1 mr-3 text-emerald-500 text-lg"></i>
            <div>
                <h4 class="font-bold">Berhasil</h4>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
        <form action="{{ route('reports.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Aset (Opsional)</label>
                <select name="item_id" class="searchable-select w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    <option value="">-- Laporan Umum (Tidak spesifik ke satu aset) --</option>
                    @foreach(\App\Models\Item::orderBy('nama_barang')->get() as $item)
                        <option value="{{ $item->id }}">{{ $item->kode_barang }} - {{ $item->nama_barang }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Kosongkan jika laporan bersifat umum (misal: "Rak Gudang A ambruk").</p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul Laporan <span class="text-rose-500">*</span></label>
                <input type="text" name="judul_laporan" required placeholder="Contoh: Laptop Acer ditemukan rusak parah" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Detail <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" required rows="4" placeholder="Jelaskan secara rinci kronologi atau kondisi yang Anda temukan di lapangan..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex justify-center items-center gap-2">
                <i class="fas fa-paper-plane"></i> Kirim Laporan ke Admin
            </button>
        </form>
    </div>
</div>
@endsection
