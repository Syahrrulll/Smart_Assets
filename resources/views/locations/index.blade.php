@extends('layouts.app')

@section('title', 'Kelola Lokasi | Smart Asset Management')
@section('header_title', 'Manajemen Lokasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold font-outfit text-slate-800">Daftar Lokasi & Ruangan</h2>
            <p class="text-slate-500 text-sm">Kelola daftar lokasi untuk penempatan aset Anda.</p>
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

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start">
            <i class="fas fa-exclamation-circle mt-1 mr-3 text-rose-500 text-lg"></i>
            <div>
                <h4 class="font-bold">Gagal</h4>
                <p class="text-sm">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Tambah Lokasi -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center">
                    <i class="fas fa-plus-circle text-indigo-500 mr-2"></i> Tambah Lokasi Baru
                </h3>
                <form action="{{ route('locations.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ruangan / Gudang</label>
                        <input type="text" name="nama_lokasi" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" placeholder="Misal: Gudang Utama" required>
                    </div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-600/20 transition-all">
                        Simpan Lokasi
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Lokasi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Lokasi</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($locations as $loc)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    <i class="fas fa-map-marker-alt text-slate-400 mr-2"></i> {{ $loc->nama_lokasi }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini? Data aset di lokasi ini mungkin akan kehilangan referensi lokasi.')" class="inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-colors flex items-center justify-center">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-12 text-center text-slate-500">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-3">
                                        <i class="fas fa-map-marker-slash text-2xl"></i>
                                    </div>
                                    <p>Belum ada data lokasi.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
