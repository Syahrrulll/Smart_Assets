@extends('layouts.app')

@section('title', 'Ajukan Pemindahan | Smart Asset Management')
@section('header_title', 'Ajukan Pemindahan')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-xl font-bold text-slate-800">Pengajuan Pemindahan Aset</h2>
        <p class="text-sm text-slate-500">Ajukan perpindahan aset ke lokasi baru.</p>
    </div>

    <form action="{{ route('relocations.store') }}" method="POST">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Aset <span class="text-rose-500">*</span></label>
                <select name="item_id" id="item_id" required onchange="updateCurrentLocation(this)" class="searchable-select w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm">
                    <option value="" data-loc="">-- Pilih Aset --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" data-loc="{{ $item->lokasi_barang }}">{{ $item->nama_barang }} ({{ $item->kode_barang }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Saat Ini</label>
                <input type="text" name="from_location" id="from_location" readonly class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Tujuan <span class="text-rose-500">*</span></label>
                <input type="text" name="to_location" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm" placeholder="Cth. Gudang Baru">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Diajukan Oleh</label>
                <input type="text" name="requested_by" value="{{ Auth::user()->name ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm">
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('relocations.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-500/20 transition-all">Ajukan Pemindahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function updateCurrentLocation(select) {
        var loc = select.options[select.selectedIndex].getAttribute('data-loc');
        document.getElementById('from_location').value = loc || 'Belum ditentukan';
    }
</script>
@endpush
@endsection
