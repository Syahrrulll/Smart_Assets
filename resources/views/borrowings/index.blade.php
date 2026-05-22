@extends('layouts.app')

@section('title', 'Asset Check Out & In | Smart Asset Management')
@section('header_title', 'Check Out & Check In')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Peminjaman Aset</h2>
        <p class="text-sm text-slate-500">Lacak siapa yang meminjam aset dan kapan harus dikembalikan.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('borrowings.create') }}" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-emerald-500/20 transition-all flex items-center">
            <i class="fas fa-handshake mr-2"></i>Peminjaman Baru
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="py-4 pl-6 pr-3">Aset</th>
                    <th class="py-4 px-3">Peminjam</th>
                    <th class="py-4 px-3">Tgl Pinjam</th>
                    <th class="py-4 px-3">Batas Kembali</th>
                    <th class="py-4 px-3">Status</th>
                    <th class="py-4 pl-3 pr-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($borrowings as $b)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 pl-6 pr-3 font-medium text-slate-800">{{ $b->item->nama_barang ?? 'Aset Tidak Diketahui' }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $b->borrower_name }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $b->borrowed_at->format('d M Y') }}</td>
                    <td class="py-4 px-3 text-sm text-slate-600">{{ $b->expected_return_at ? $b->expected_return_at->format('d M Y') : 'Tidak Terbatas' }}</td>
                    <td class="py-4 px-3">
                        @php
                            $statusClass = match($b->status) {
                                'borrowed' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'returned' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'overdue' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>
                    <td class="py-4 pl-3 pr-6 text-right space-x-2">
                        @if($b->status !== 'returned')
                        <form action="{{ route('borrowings.update', $b->id) }}" method="POST" class="inline-block">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="returned">
                            <input type="hidden" name="returned_at" value="{{ now() }}">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-semibold rounded-lg transition-colors">
                                Kembalikan
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-500">Tidak ada catatan peminjaman ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($borrowings->hasPages())
    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50">
        {{ $borrowings->links('pagination::tailwind') }}
    </div>
    @endif
</div>

@endsection
