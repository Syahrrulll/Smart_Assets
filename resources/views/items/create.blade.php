@extends('layouts.app')

@section('title', 'Tambah Aset | Smart Asset Management')
@section('header_title', 'Tambah Aset Baru')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <div class="mb-6 flex justify-between items-center border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Aset Baru</h2>
            <p class="text-sm text-slate-500">Daftarkan aset fisik baru ke dalam sistem.</p>
        </div>
        
        <!-- Smart OCR Feature -->
        <button type="button" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-semibold rounded-xl text-sm border border-indigo-200 shadow-sm transition-colors flex items-center" onclick="simulateOCR()">
            <i class="fas fa-magic mr-2"></i> Pindai Faktur (OCR)
        </button>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800">
            <div class="font-bold mb-1 flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i> Harap perbaiki kesalahan berikut:</div>
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Column 1 -->
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Aset <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" required value="{{ old('nama_barang') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Aset <span class="text-rose-500">*</span></label>
                    <input type="text" name="kode_barang" id="kode_barang" required value="{{ old('kode_barang') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm font-mono uppercase">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NUP (Nomor Urut Pendaftaran)</label>
                    <input type="number" name="nup" id="nup" value="{{ old('nup', $next_nup) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    <p class="text-xs text-slate-400 mt-1">Biarkan kosong untuk nilai otomatis.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Umur Ekonomis</label>
                    <div class="flex items-center">
                        <input type="number" name="umur_ekonomis" id="umur_ekonomis" value="{{ old('umur_ekonomis', 5) }}" class="w-24 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-l-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm border-r-0">
                        <span class="px-4 py-2.5 bg-slate-100 border border-slate-200 border-l-0 rounded-r-xl text-sm text-slate-500">Tahun</span>
                    </div>
                </div>
            </div>

            <!-- Column 2 -->
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tahun Pembelian</label>
                    <input type="number" name="tahun_barang" id="tahun_barang" value="{{ old('tahun_barang', date('Y')) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi</label>
                    <select name="lokasi_barang" id="lokasi_barang" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->nama_lokasi }}" {{ old('lokasi_barang') == $loc->nama_lokasi ? 'selected' : '' }}>{{ $loc->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi</label>
                    <select name="kondisi_barang" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                        <option value="Baik" {{ old('kondisi_barang') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi_barang') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi_barang') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Aset</label>
                    <input type="file" name="foto_barang" accept="image/*" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>
            </div>
            
        </div>

        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('items.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex items-center">
                <i class="fas fa-save mr-2"></i> Simpan Aset
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Simulate OCR Invoice Reading Feature
    function simulateOCR() {
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menganalisis...';
        btn.disabled = true;

        setTimeout(() => {
            document.getElementById('nama_barang').value = 'Lenovo Thinkpad T14 Gen 2 (Scanned)';
            document.getElementById('kode_barang').value = 'IT-LPT-' + Math.floor(Math.random() * 1000);
            document.getElementById('tahun_barang').value = new Date().getFullYear();
            
            btn.innerHTML = '<i class="fas fa-check text-emerald-500 mr-2"></i> Data Diekstrak!';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 3000);
        }, 1500);
    }
</script>
@endpush
@endsection
