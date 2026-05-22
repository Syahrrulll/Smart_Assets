@extends('layouts.app')

@section('title', 'Manajemen Pengguna | Smart Asset Management')
@section('header_title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold font-outfit text-slate-800">Daftar Admin & Staf</h2>
            <p class="text-slate-500 text-sm">Kelola akun dan hak akses tim Anda.</p>
        </div>
        <a href="{{ route('users.create') }}" class="py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-user-plus"></i> Tambah Pengguna
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama Pengguna</th>
                        <th class="px-6 py-4 font-semibold">Email (Login)</th>
                        <th class="px-6 py-4 font-semibold">Peran</th>
                        <th class="px-6 py-4 font-semibold">Terdaftar Sejak</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl {{ $user->role == 'admin' ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                {{ $user->name }}
                                @if(auth()->id() == $user->id)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800">Saya</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role == 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                    <i class="fas fa-shield-alt mr-1"></i> Administrator
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                    <i class="fas fa-user mr-1"></i> Staf Gudang
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors" title="Edit Akun & Sandi">
                                <i class="fas fa-pen"></i>
                            </a>
                            @if(auth()->id() != $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini? Login mereka tidak akan berfungsi lagi.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-colors" title="Hapus Akun">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
