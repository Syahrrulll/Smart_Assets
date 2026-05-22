@extends('layouts.app')

@section('title', 'Ajukan Penghapusan | Smart Asset Management')
@section('header_title', 'Ajukan Penghapusan')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-xl font-bold text-slate-800">Pengajuan Penghapusan Aset</h2>
        <p class="text-sm text-slate-500">Ajukan penghapusan untuk aset yang rusak berat atau menyusut.</p>
    </div>

    <form action="{{ route('disposals.store') }}" method="POST">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Aset <span class="text-rose-500">*</span></label>
                <select name="item_id" required class="searchable-select w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 text-sm">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->kode_barang }}) - {{ $item->kondisi_barang }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alasan Penghapusan <span class="text-rose-500">*</span></label>
                <textarea name="reason" required rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 text-sm" placeholder="Cth. Menyusut penuh, rusak total..."></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('disposals.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-rose-500/20 transition-all">Ajukan Penghapusan</button>
        </div>
    </form>
</div>
@endsection
