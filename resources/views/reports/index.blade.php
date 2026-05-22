@extends('layouts.app')

@section('title', 'Kotak Masuk Laporan | Smart Asset Management')
@section('header_title', 'Kotak Masuk Laporan')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold font-outfit text-slate-800">Daftar Laporan Staf</h2>
            <p class="text-slate-500 text-sm">Kelola aduan dan laporan terkait anomali aset di lapangan.</p>
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

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Pelapor</th>
                        <th class="px-6 py-4 font-semibold">Aset Terkait</th>
                        <th class="px-6 py-4 font-semibold">Laporan</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">{{ $report->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 font-medium">{{ $report->user->name ?? 'User Terhapus' }}</td>
                        <td class="px-6 py-4">
                            @if($report->item)
                                <a href="{{ route('items.edit', $report->item->id) }}" class="text-indigo-600 hover:underline font-medium">
                                    {{ $report->item->kode_barang }}
                                </a>
                            @else
                                <span class="text-slate-400 italic">Laporan Umum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $report->judul_laporan }}</div>
                            <div class="text-xs text-slate-500 max-w-xs truncate">{{ $report->deskripsi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($report->status == 'pending')
                                <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">Pending</span>
                            @elseif($report->status == 'diproses')
                                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">Diproses</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('reports.update', $report->id) }}" method="POST" class="inline-flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:ring-2 focus:ring-indigo-500 outline-none" onchange="this.form.submit()">
                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-3">
                                <i class="fas fa-inbox text-2xl"></i>
                            </div>
                            <p>Tidak ada laporan masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
