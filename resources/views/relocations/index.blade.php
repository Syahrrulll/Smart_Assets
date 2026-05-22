@extends('layouts.app')

@section('title', 'Relocations | Smart Asset Management')
@section('header_title', 'Asset Relocations')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Pemindahan Aset</h2>
        <p class="text-sm text-slate-500">Setujui dan pantau pergerakan aset antar lokasi.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('relocations.create') }}" class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-500/20 transition-all flex items-center">
            <i class="fas fa-truck-moving mr-2"></i>Ajukan Pemindahan
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="py-4 pl-6 pr-3">Aset</th>
                    <th class="py-4 px-3">Dari</th>
                    <th class="py-4 px-3">Ke</th>
                    <th class="py-4 px-3">Diajukan Oleh</th>
                    <th class="py-4 px-3">Status</th>
                    <th class="py-4 pl-3 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($relocations as $r)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 pl-6 pr-3 font-medium text-slate-800">{{ $r->item->nama_barang ?? 'Aset Tidak Diketahui' }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $r->from_location ?? 'Bebas' }}</td>
                    <td class="py-4 px-3 text-sm font-bold text-slate-800"><i class="fas fa-arrow-right text-slate-400 mr-2 text-xs"></i>{{ $r->to_location }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $r->requested_by ?? 'Sistem' }}</td>
                    <td class="py-4 px-3">
                        @php
                            $statusClass = match($r->status) {
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td class="py-4 pl-3 pr-6 text-right space-x-2">
                        @if($r->status === 'pending')
                        <form action="{{ route('relocations.update', $r->id) }}" method="POST" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <input type="hidden" name="approved_by" value="{{ Auth::user()->name ?? 'Admin' }}">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 font-semibold rounded-lg transition-colors">
                                Setujui
                            </button>
                        </form>
                        <form action="{{ route('relocations.update', $r->id) }}" method="POST" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold rounded-lg transition-colors">
                                Tolak
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-500">Tidak ada pengajuan pemindahan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($relocations->hasPages())
    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50">
        {{ $relocations->links('pagination::tailwind') }}
    </div>
    @endif
</div>

@endsection
