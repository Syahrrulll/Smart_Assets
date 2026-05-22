@extends('layouts.app')

@section('title', 'Peminjaman Baru | Smart Asset Management')
@section('header_title', 'Pinjam Aset')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-xl font-bold text-slate-800">Pinjam Aset</h2>
        <p class="text-sm text-slate-500">Catat transaksi peminjaman baru.</p>
    </div>

    <form action="{{ route('borrowings.store') }}" method="POST">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Aset <span class="text-rose-500">*</span></label>
                <select name="item_id" required class="searchable-select w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    <option value="">-- Pilih Aset --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->kode_barang }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Peminjam <span class="text-rose-500">*</span></label>
                <input type="text" name="borrower_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" placeholder="John Doe">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Perkiraan Kembali</label>
                <input type="datetime-local" name="expected_return_at" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('borrowings.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition-all">Simpan Peminjaman</button>
        </div>
    </form>
</div>
@endsection
