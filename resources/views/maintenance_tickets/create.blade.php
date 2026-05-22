@extends('layouts.app')

@section('title', 'Lapor Kerusakan | Smart Asset Management')
@section('header_title', 'Lapor Kerusakan')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-xl font-bold text-slate-800">Lapor Kerusakan</h2>
        <p class="text-sm text-slate-500">Buat tiket perbaikan baru untuk aset yang rusak.</p>
    </div>

    <form action="{{ route('maintenance-tickets.store') }}" method="POST">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Aset <span class="text-rose-500">*</span></label>
                <select name="item_id" required class="searchable-select w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 text-sm">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->kode_barang }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Dilaporkan Oleh</label>
                <input type="text" name="reported_by" value="{{ Auth::user()->name ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Masalah <span class="text-rose-500">*</span></label>
                <textarea name="issue_description" required rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 text-sm" placeholder="Jelaskan kerusakan atau masalah..."></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('maintenance-tickets.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-amber-500/20 transition-all">Kirim Laporan</button>
        </div>
    </form>
</div>
@endsection
