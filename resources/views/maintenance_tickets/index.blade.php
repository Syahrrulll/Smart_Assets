@extends('layouts.app')

@section('title', 'Maintenance | Smart Asset Management')
@section('header_title', 'Maintenance Tickets')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Perbaikan Aset</h2>
        <p class="text-sm text-slate-500">Laporkan kerusakan dan pantau perbaikan aset.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('maintenance-tickets.create') }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-amber-500/20 transition-all flex items-center">
            <i class="fas fa-tools mr-2"></i>Lapor Kerusakan
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="py-4 pl-6 pr-3">Aset</th>
                    <th class="py-4 px-3">Masalah</th>
                    <th class="py-4 px-3">Dilaporkan Oleh</th>
                    <th class="py-4 px-3">Biaya</th>
                    <th class="py-4 px-3">Status</th>
                    <th class="py-4 pl-3 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tickets as $t)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 pl-6 pr-3 font-medium text-slate-800">{{ $t->item->nama_barang ?? 'Aset Tidak Diketahui' }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600 max-w-xs truncate" title="{{ $t->issue_description }}">{{ $t->issue_description }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $t->reported_by ?? 'Sistem' }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $t->cost ? 'Rp ' . number_format($t->cost, 0, ',', '.') : '-' }}</td>
                    <td class="py-4 px-3">
                        @php
                            $statusClass = match($t->status) {
                                'open' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ str_replace('_', ' ', ucfirst($t->status)) }}
                        </span>
                    </td>
                    <td class="py-4 pl-3 pr-6 text-right space-x-2">
                        @if($t->status === 'open')
                        <form action="{{ route('maintenance-tickets.update', $t->id) }}" method="POST" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 font-semibold rounded-lg transition-colors">
                                Mulai Perbaikan
                            </button>
                        </form>
                        @elseif($t->status === 'in_progress')
                        <form action="{{ route('maintenance-tickets.update', $t->id) }}" method="POST" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="resolved">
                            <input type="hidden" name="resolved_at" value="{{ now() }}">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 font-semibold rounded-lg transition-colors">
                                Selesai
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-500">Tidak ada tiket perbaikan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tickets->hasPages())
    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50">
        {{ $tickets->links('pagination::tailwind') }}
    </div>
    @endif
</div>

@endsection
