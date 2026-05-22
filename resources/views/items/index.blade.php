@extends('layouts.app')

@section('title', 'Inventory | Smart Asset Management')
@section('header_title', 'Asset Inventory')

@section('content')

@if(request('print_all'))
    <!-- PRINT MODE (Keep as vanilla/inline styling for print layout) -->
    <script>window.onload = function() { window.print(); }</script>
    <style>
        body { background: white !important; font-family: sans-serif; }
        .print-container { padding: 20px; }
        .label-grid { text-align: center; line-height: 0; }
        .label-item { display: inline-flex; flex-direction: column; justify-content: space-between; align-items: center; width: 3.8cm; height: 3.8cm; border: 1px dashed #333; padding: 0.2cm; margin: 0.1cm; box-sizing: border-box; break-inside: avoid; page-break-inside: avoid; vertical-align: top; background-color: #fff; line-height: normal; }
        .label-title { font-size: 8px; font-weight: bold; text-transform: uppercase; line-height: 1.2; height: 19px; overflow: hidden; width: 100%; }
        .label-qr { flex: 1; display: flex; justify-content: center; align-items: center; width: 100%; }
        .label-code { font-family: monospace; font-size: 8px; font-weight: bold; background: #000; color: #fff; padding: 2px 6px; display: inline-block; border-radius: 2px; }
        .print-toolbar { position: sticky; top: 80px; background: #ffffff; padding: 15px 20px; border: 1px solid #e2e8f0; margin-bottom: 20px; display: flex; justify-content: space-between; z-index: 50; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
        @media print { 
            .print-toolbar, aside, header { display: none !important; } 
            main { margin-left: 0 !important; padding: 0 !important; }
            .p-10 { padding: 0 !important; }
            .max-w-7xl { max-width: none !important; }
            .label-item { border: 1px solid #000; } 
            @page { margin: 0.5cm; size: A4 portrait; } 
        }
    </style>
    <div class="print-container">
        <div class="print-toolbar">
            <div>
                <h4 style="margin:0;">Preview Label</h4>
                @if($items->lastPage() > 1)
                <form action="{{ route('items.index') }}" method="GET" style="margin-top:10px;">
                    <input type="hidden" name="print_all" value="true">
                    <select name="page" onchange="this.form.submit()">
                        @for($i = 1; $i <= $items->lastPage(); $i++) <option value="{{ $i }}" {{ $items->currentPage() == $i ? 'selected' : '' }}>Batch {{ $i }}</option> @endfor
                    </select>
                </form>
                @endif
            </div>
            <div>
                <button onclick="window.print()" style="padding:10px; font-weight:bold; cursor:pointer;">Print</button>
                <button onclick="window.close()" style="padding:10px; margin-left:10px; cursor:pointer;">Close</button>
            </div>
        </div>
        <div class="label-grid">
            @foreach($items as $item)
                <div class="label-item">
                    <div class="label-title">{{ $item->nama_barang }}</div>
                    <div class="label-qr">{!! QrCode::size(50)->generate(route('items.edit', $item->id)) !!}</div>
                    <div class="label-code">{{ $item->tahun_barang ?? date('Y') }} / {{ str_pad($item->nup, 3, '0', STR_PAD_LEFT) }}</div>
                </div>
            @endforeach
        </div>
    </div>
@else

    <!-- Smart Command Center Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        <!-- Depreciation Tracking -->
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg shadow-emerald-500/20 p-5 text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-white/10 text-8xl"><i class="fas fa-chart-line"></i></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-coins text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-outfit">Depreciation</h3>
                    </div>
                </div>
                
                <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm mb-2 border border-white/20">
                    <div class="flex flex-col mb-1">
                        <span class="text-[10px] text-emerald-100 mb-0.5">Nilai Saat Ini</span>
                        <span class="text-xl font-black leading-tight">Rp {{ number_format($financial_stats['total_saat_ini'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-black/20 rounded-full h-1 mb-1">
                        @php 
                            $percent = ($financial_stats['total_awal'] > 0) ? ($financial_stats['total_saat_ini'] / $financial_stats['total_awal']) * 100 : 0;
                        @endphp
                        <div class="bg-white h-1 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="text-[9px] text-emerald-50 flex justify-between">
                        <span>Modal: Rp {{ number_format($financial_stats['total_awal'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Preventive Maintenance -->
        <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl shadow-lg shadow-orange-500/20 p-5 text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-white/10 text-8xl"><i class="fas fa-tools"></i></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-wrench text-sm text-orange-100"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-outfit">Maintenance</h3>
                    </div>
                </div>

                @if(isset($maintenance_alerts) && count($maintenance_alerts) > 0)
                    <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm mb-2 border border-white/20">
                        <div class="flex items-end gap-2 mb-1">
                            <span class="text-2xl font-black leading-none">{{ count($maintenance_alerts) }}</span>
                            <span class="text-orange-100 text-[10px] font-medium mb-0.5">Jatuh Tempo</span>
                        </div>
                        <p class="text-[10px] text-orange-50 font-medium leading-tight line-clamp-2">Aset perlu diservis segera untuk mencegah kerusakan fatal.</p>
                    </div>
                    <button onclick="document.getElementById('maintenanceModal').classList.remove('hidden')" class="w-full py-2 bg-white text-orange-600 font-bold rounded-xl text-xs hover:bg-orange-50 transition-colors shadow-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                @else
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm mb-2 border border-white/20 flex flex-col items-center justify-center text-center h-[88px]">
                        <i class="fas fa-check-circle text-2xl text-emerald-300 mb-1"></i>
                        <span class="font-bold text-xs">Jadwal Aman</span>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Ghost Asset Detector -->
        <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl shadow-lg shadow-rose-500/20 p-5 text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-white/10 text-8xl"><i class="fas fa-ghost"></i></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-search-location text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-outfit">Ghost Asset</h3>
                    </div>
                </div>
                
                @if(isset($ghost_assets) && count($ghost_assets) > 0)
                    <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm mb-2 border border-white/20">
                        <div class="flex items-end gap-2 mb-1">
                            <span class="text-2xl font-black leading-none">{{ count($ghost_assets) }}</span>
                            <span class="text-rose-100 text-[10px] font-medium mb-0.5">Berisiko Hilang</span>
                        </div>
                        <p class="text-[10px] text-rose-50 font-medium leading-tight line-clamp-2">> 6 bulan tak dicek.</p>
                    </div>
                    <button onclick="document.getElementById('ghostModal').classList.remove('hidden')" class="w-full py-2 bg-white text-rose-600 font-bold rounded-xl text-xs hover:bg-rose-50 transition-colors shadow-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                @else
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm mb-2 border border-white/20 flex flex-col items-center justify-center text-center h-[88px]">
                        <i class="fas fa-shield-check text-2xl text-emerald-300 mb-1"></i>
                        <span class="font-bold text-xs">Gudang Aman</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Smart Insight AI -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg shadow-indigo-500/20 p-5 text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-white/10 text-8xl"><i class="fas fa-brain"></i></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-lightbulb text-sm text-amber-300"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base font-outfit">Smart Insight</h3>
                    </div>
                </div>

                @if(isset($smart_insights) && count($smart_insights) > 0)
                    <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm mb-2 border border-white/20">
                        <div class="flex items-end gap-2 mb-1">
                            <span class="text-2xl font-black leading-none">{{ count($smart_insights) }}</span>
                            <span class="text-indigo-100 text-[10px] font-medium mb-0.5">Kategori Kritis</span>
                        </div>
                        <p class="text-[10px] text-indigo-50 font-medium leading-tight line-clamp-2">Kerusakan >30%.</p>
                    </div>
                    <button onclick="document.getElementById('insightModal').classList.remove('hidden')" class="w-full py-2 bg-white text-indigo-600 font-bold rounded-xl text-xs hover:bg-indigo-50 transition-colors shadow-sm">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                @else
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm mb-2 border border-white/20 flex flex-col items-center justify-center text-center h-[88px]">
                        <i class="fas fa-thumbs-up text-2xl text-emerald-300 mb-1"></i>
                        <span class="font-bold text-xs">Aset Prima</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Toolbar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Ikhtisar Aset</h2>
            <p class="text-sm text-slate-500">Kelola dan pantau inventaris enterprise Anda.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <form id="bulkDeleteForm" action="{{ route('items.bulkDelete') }}" method="POST">
                @csrf
                <button type="button" id="btnBulkDelete" class="hidden px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors" onclick="submitBulkDelete()">
                    <i class="fas fa-trash-alt mr-2"></i>Hapus (<span id="countSelected">0</span>)
                </button>
            </form>
            
            <button onclick="document.getElementById('activityLogModal').classList.remove('hidden')" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold rounded-xl shadow-sm transition-colors">
                <i class="fas fa-history text-indigo-500 mr-2"></i>Riwayat
            </button>
            
            <div class="flex shadow-sm rounded-xl overflow-hidden border border-slate-200 text-sm font-semibold">
                <button onclick="document.getElementById('printSettingsModal').classList.remove('hidden')" class="px-3 py-2.5 bg-white hover:bg-slate-50 text-slate-700 transition-colors border-r border-slate-200 flex items-center" title="Cetak Label">
                    <i class="fas fa-print text-slate-500 mr-2"></i> Cetak
                </button>
                <a href="{{ route('items.export') }}" class="px-3 py-2.5 bg-white hover:bg-slate-50 text-slate-700 transition-colors border-r border-slate-200 flex items-center" title="Ekspor Excel">
                    <i class="fas fa-file-excel text-emerald-600 mr-2"></i> Ekspor
                </a>
                <a href="{{ route('items.import.form') }}" class="px-3 py-2.5 bg-white hover:bg-slate-50 text-slate-700 transition-colors flex items-center" title="Unggah Data">
                    <i class="fas fa-file-upload text-blue-600 mr-2"></i> Unggah
                </a>
            </div>
            
            <a href="{{ route('items.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Aset
            </a>
        </div>
    </div>

    <!-- Filters Area -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <form action="{{ route('items.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama atau kode..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm">
                </div>
            </div>
            
            <div class="w-full md:w-48">
                <select name="lokasi" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm appearance-none cursor-pointer">
                    <option value="">Semua Lokasi</option>
                    @foreach($list_lokasi ?? [] as $loc)
                        <option value="{{ $loc }}" {{ request('lokasi') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-48">
                <select name="kondisi" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm appearance-none cursor-pointer">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            
            <button type="submit" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl transition-colors text-sm">
                Filter
            </button>
            
            @if(request()->anyFilled(['keyword', 'lokasi', 'kondisi', 'tahun']))
            <a href="{{ route('items.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl transition-colors text-sm flex items-center">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="py-4 pl-6 pr-3"><input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"></th>
                        <th class="py-4 px-3">Aset</th>
                        <th class="py-4 px-3">Kode / NUP</th>
                        <th class="py-4 px-3">Lokasi</th>
                        <th class="py-4 px-3">Kondisi</th>
                        <th class="py-4 px-3">Tahun</th>
                        <th class="py-4 px-3 text-center">QR</th>
                        <th class="py-4 pl-3 pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 pl-6 pr-3"><input type="checkbox" name="ids[]" form="bulkDeleteForm" value="{{ $item->id }}" class="item-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"></td>
                        <td class="py-4 px-3">
                            <div class="flex items-center gap-4">
                                @if($item->foto_barang)
                                    <img src="{{ asset($item->foto_barang) }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-sm" alt="Asset">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-image text-lg"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-800">{{ $item->nama_barang }}</div>
                                    <div class="text-xs text-slate-500">Added {{ $item->created_at->format('M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-3">
                            <div class="font-mono text-sm text-slate-700">{{ $item->kode_barang }}</div>
                            <div class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ str_pad($item->nup, 3, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>
                        <td class="py-4 px-3 text-sm text-slate-600">{{ $item->lokasi_barang ?? '-' }}</td>
                        <td class="py-4 px-3">
                            @php
                                $condClass = match($item->kondisi_barang) {
                                    'Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Rusak Ringan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Rusak Berat' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $condClass }}">
                                {{ $item->kondisi_barang ?? 'Unknown' }}
                            </span>
                        </td>
                        <td class="py-4 px-3">
                            <div class="text-sm text-slate-600">{{ $item->tahun_barang ?? '-' }}</div>
                            @if($item->harga_beli)
                                <div class="text-xs font-bold text-emerald-600 mt-1" title="Nilai Saat Ini">Rp {{ number_format($item->nilai_buku, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-3">
                            <div class="flex justify-center">
                                <div class="bg-white p-1 rounded shadow-sm border border-slate-200">
                                    {!! QrCode::size(32)->generate(route('items.edit', $item->id)) !!}
                                </div>
                            </div>
                        </td>
                        <td class="py-4 pl-3 pr-6 text-right space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('items.edit', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" onclick="deleteItem({{ $item->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('items.destroy', $item->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-slate-300"></i>
                                <p>Tidak ada aset ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($items->hasPages())
        <div class="border-t border-slate-100 px-6 py-4 bg-slate-50">
            {{ $items->links('pagination::tailwind') }}
        </div>
        @endif
    </div>

@endif

<!-- Print Settings Modal -->
<div id="printSettingsModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-xl transform transition-all">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Cetak Label QR</h3>
            <button onclick="document.getElementById('printSettingsModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-slate-500 mb-6">Pilih mode cetak untuk mencetak label QR Code barang.</p>
            <div class="space-y-4">
                <a href="{{ route('items.index', ['print_all' => 'true']) }}" target="_blank" class="w-full flex items-center justify-between p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/50 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="text-left">
                            <div class="font-bold text-slate-800">Cetak Semua Barcode</div>
                            <div class="text-xs text-slate-500">Mencetak seluruh label dalam format grid</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Activity Log Modal -->
<div id="activityLogModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl transform transition-all flex flex-col max-h-[80vh]">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-history text-indigo-500 mr-2"></i>Riwayat Aktivitas Global</h3>
            <button onclick="document.getElementById('activityLogModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                @forelse($global_activities ?? [] as $log)
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-100 group-[.is-active]:bg-indigo-600 text-slate-500 group-[.is-active]:text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        <i class="fas fa-pen text-xs"></i>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between space-x-2 mb-1">
                            <div class="font-bold text-slate-800 text-sm">{{ $log->item->nama_barang ?? 'Aset Dihapus' }}</div>
                            <time class="text-xs font-medium text-slate-500">{{ $log->created_at->diffForHumans() }}</time>
                        </div>
                        <div class="text-slate-600 text-sm">
                            <span class="font-semibold text-slate-700">{{ $log->user_name }}</span> mengubah 
                            <span class="font-medium text-indigo-600">{{ $log->field_name }}</span>
                        </div>
                        @if($log->old_value != '-')
                        <div class="mt-2 text-xs bg-slate-50 p-2 rounded border border-slate-100">
                            <span class="text-rose-500 line-through mr-2">{{ Str::limit($log->old_value, 20) }}</span>
                            <span class="text-emerald-600 font-medium">{{ Str::limit($log->new_value, 20) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-500 py-4">Belum ada riwayat aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Smart Insight Modal -->
<div id="insightModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl transform transition-all">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-amber-50">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-lightbulb text-amber-500 mr-2"></i>Rekomendasi Cerdas (AI)</h3>
            <button onclick="document.getElementById('insightModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-slate-600 mb-4">Daftar kategori barang dengan tingkat kerusakan lebih dari 30%. Disarankan untuk masuk dalam rencana pengadaan tahun depan.</p>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold">
                            <th class="py-3 px-4">Nama Kategori</th>
                            <th class="py-3 px-4 text-center">Total Unit</th>
                            <th class="py-3 px-4 text-center">Rusak Berat</th>
                            <th class="py-3 px-4 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($smart_insights ?? [] as $insight)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium text-slate-800">{{ $insight['nama'] }}</td>
                            <td class="py-3 px-4 text-center">{{ $insight['total'] }}</td>
                            <td class="py-3 px-4 text-center text-rose-500 font-bold">{{ $insight['rusak'] }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-rose-100 text-rose-700 px-2 py-1 rounded text-xs font-bold">{{ $insight['persen'] }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Modal -->
<div id="maintenanceModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl transform transition-all">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-orange-50">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-tools text-orange-500 mr-2"></i>Peringatan Perawatan Aset</h3>
            <button onclick="document.getElementById('maintenanceModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-slate-600 mb-4">Aset-aset berikut akan segera jatuh tempo atau telah melewati jadwal perawatannya. Segera buat tiket maintenance!</p>
            <div class="max-h-64 overflow-y-auto overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold sticky top-0">
                            <th class="py-3 px-4">Nama Aset</th>
                            <th class="py-3 px-4">Jatuh Tempo</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maintenance_alerts ?? [] as $alert)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium">{{ $alert['item']->nama_barang }} <br><span class="text-xs text-slate-500 font-mono">{{ $alert['item']->kode_barang }}</span></td>
                            <td class="py-3 px-4">{{ $alert['due_date']->format('d M Y') }}</td>
                            <td class="py-3 px-4">
                                @if($alert['is_overdue'])
                                    <span class="bg-rose-100 text-rose-700 px-2 py-1 rounded text-xs font-bold">Terlewat</span>
                                @else
                                    <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-bold">Segera</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Ghost Asset Modal -->
<div id="ghostModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-xl transform transition-all">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-rose-50">
            <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-ghost text-rose-500 mr-2"></i>Deteksi Aset Siluman</h3>
            <button onclick="document.getElementById('ghostModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-slate-600 mb-4">Aset-aset berikut tidak pernah diperbarui atau di-scan selama lebih dari 6 bulan. Mohon segera lakukan audit fisik.</p>
            <div class="max-h-64 overflow-y-auto overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold sticky top-0">
                            <th class="py-3 px-4">Kode Barang</th>
                            <th class="py-3 px-4">Nama Barang</th>
                            <th class="py-3 px-4">Terakhir Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ghost_assets ?? [] as $ghost)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 font-mono text-xs">{{ $ghost->kode_barang }}</td>
                            <td class="py-3 px-4 font-medium">{{ $ghost->nama_barang }}</td>
                            <td class="py-3 px-4 text-rose-500">{{ $ghost->updated_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Bulk Selection Logic
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const bulkBtn = document.getElementById('btnBulkDelete');
        const countSpan = document.getElementById('countSelected');

        if(selectAll) {
            selectAll.addEventListener('change', e => {
                checkboxes.forEach(cb => cb.checked = e.target.checked);
                updateBtn();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBtn);
        });

        function updateBtn() {
            const count = document.querySelectorAll('.item-checkbox:checked').length;
            if(countSpan) countSpan.innerText = count;
            if(bulkBtn) {
                if(count > 0) { bulkBtn.classList.remove('hidden'); bulkBtn.classList.add('inline-flex'); }
                else { bulkBtn.classList.add('hidden'); bulkBtn.classList.remove('inline-flex'); }
            }
        }
    });

    function submitBulkDelete() {
        if(confirm('Are you sure you want to permanently delete selected assets?')) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }

    function deleteItem(id) {
        if(confirm('Are you sure you want to delete this asset?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush

@endsection
