@extends('layouts.app')

@section('title', 'Disposals | Smart Asset Management')
@section('header_title', 'Asset Disposals')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Penghapusan Aset</h2>
        <p class="text-sm text-slate-500">Kelola penghapusan aset yang rusak atau menyusut penuh.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('disposals.create') }}" class="px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-rose-500/20 transition-all flex items-center">
            <i class="fas fa-trash-alt mr-2"></i>Ajukan Penghapusan
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="py-4 pl-6 pr-3">Aset</th>
                    <th class="py-4 px-3">Alasan</th>
                    <th class="py-4 px-3">Disetujui Oleh</th>
                    <th class="py-4 px-3">Tgl Penghapusan</th>
                    <th class="py-4 px-3">Status</th>
                    <th class="py-4 pl-3 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($disposals as $d)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 pl-6 pr-3 font-medium text-slate-800">{{ $d->item->nama_barang ?? 'Aset Tidak Diketahui' }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600 max-w-xs truncate" title="{{ $d->reason }}">{{ $d->reason }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $d->approved_by ?? '-' }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $d->disposal_date ? $d->disposal_date->format('d M Y') : '-' }}</td>
                    <td class="py-4 px-3">
                        @php
                            $statusClass = match($d->status) {
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'disposed' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ ucfirst($d->status) }}
                        </span>
                    </td>
                    <td class="py-4 pl-3 pr-6 text-right space-x-2">
                        @if($d->status === 'pending')
                        <form action="{{ route('disposals.update', $d->id) }}" method="POST" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <input type="hidden" name="approved_by" value="{{ Auth::user()->name ?? 'Admin' }}">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold rounded-lg transition-colors">
                                Setujui
                            </button>
                        </form>
                        @elseif($d->status === 'approved')
                        <form action="{{ route('disposals.update', $d->id) }}" method="POST" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="disposed">
                            <input type="hidden" name="disposal_date" value="{{ now() }}">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold rounded-lg transition-colors">
                                Konfirmasi Penghapusan
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-500">Tidak ada catatan penghapusan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($disposals->hasPages())
    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50">
        {{ $disposals->links('pagination::tailwind') }}
    </div>
    @endif
</div>

@endsection
