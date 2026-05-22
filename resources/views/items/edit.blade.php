@extends(((isset($is_scanned) && $is_scanned) || Auth::user()->email !== 'admin@admin.com') ? 'layouts.qr' : 'layouts.app')

@section('title', 'Edit Aset | Smart Asset Management')
@section('header_title', 'Edit Aset')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-5 md:p-8">
    <div class="mb-6 flex justify-between items-center border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Aset: {{ $item->nama_barang }}</h2>
            <p class="text-sm text-slate-500">Perbarui detail aset dan pantau riwayatnya.</p>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('items.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold rounded-xl text-sm transition-colors flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            @if(request('scanned_from_qr'))
            <span class="px-4 py-2 bg-emerald-50 text-emerald-600 font-semibold rounded-xl text-sm border border-emerald-200 flex items-center">
                <i class="fas fa-qrcode mr-2"></i> Dipindai
            </span>
            @endif
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start">
            <i class="fas fa-check-circle mt-1 mr-3 text-emerald-500 text-lg"></i>
            <div>
                <h4 class="font-bold">Berhasil</h4>
                <p class="text-sm">{{ $message }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Update -->
        <div class="lg:col-span-2">
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                @if(request('scanned_from_qr'))
                    <input type="hidden" name="scanned_from_qr" value="1">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Aset <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_barang" required value="{{ old('nama_barang', $item->nama_barang) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" {{ Auth::user()->email !== 'admin@admin.com' ? 'readonly' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Aset <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode_barang" required value="{{ old('kode_barang', $item->kode_barang) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm font-mono uppercase" {{ Auth::user()->email !== 'admin@admin.com' ? 'readonly' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tahun Pembelian</label>
                        <input type="number" name="tahun_barang" value="{{ old('tahun_barang', $item->tahun_barang) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" {{ Auth::user()->email !== 'admin@admin.com' ? 'readonly' : '' }}>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi</label>
                        <select name="lokasi_barang" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->nama_lokasi }}" {{ old('lokasi_barang', $item->lokasi_barang) == $loc->nama_lokasi ? 'selected' : '' }}>{{ $loc->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi</label>
                        <select name="kondisi_barang" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                            <option value="Baik" {{ old('kondisi_barang', $item->kondisi_barang) == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi_barang', $item->kondisi_barang) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi_barang', $item->kondisi_barang) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2 mt-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Kondisi Barang Terkini</label>
                        <input type="file" name="foto_barang" accept="image/*" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="text-xs text-slate-500 mt-1"><i class="fas fa-info-circle mr-1"></i> Biarkan kosong jika foto tidak berubah. Unggah foto baru jika ada kerusakan/perubahan.</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex items-center">
                        <i class="fas fa-save mr-2"></i> Perbarui Aset
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 flex flex-col items-center text-center">
                @if($item->foto_barang)
                    <img src="{{ asset($item->foto_barang) }}" alt="Asset Image" class="w-32 h-32 object-cover rounded-xl shadow-sm border-4 border-white mb-4">
                @else
                    <div class="w-32 h-32 rounded-xl bg-slate-200 border-4 border-white mb-4 flex items-center justify-center text-slate-400">
                        <i class="fas fa-image text-4xl"></i>
                    </div>
                @endif
            </div>

            <!-- Lifecycle Summary -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 border-b border-slate-100 pb-2">Riwayat Terbaru</h3>
                <ul class="space-y-3">
                    @forelse($histories->take(4) as $h)
                    <li class="flex gap-3 text-xs">
                        <div class="w-2 h-2 mt-1.5 rounded-full bg-indigo-400 flex-shrink-0"></div>
                        <div>
                            <span class="font-bold text-slate-700">{{ $h->user_name }}</span> mengubah <span class="font-semibold text-indigo-600">{{ $h->field_name }}</span><br>
                            <span class="text-slate-400">{{ $h->created_at->diffForHumans() }}</span>
                        </div>
                    </li>
                    @empty
                    <li class="text-xs text-slate-500">Tidak ada perubahan terbaru.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
