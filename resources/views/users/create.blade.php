@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru | Smart Asset Management')
@section('header_title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center">
        <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition-all mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold font-outfit text-slate-800">Pengguna Baru</h2>
            <p class="text-slate-500 text-sm">Tambahkan staf atau administrator baru ke dalam sistem.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800">
        <div class="font-bold mb-2 flex items-center">
            <i class="fas fa-exclamation-circle mt-0.5 mr-2 text-rose-500"></i>
            Terdapat beberapa kesalahan:
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Misal: Budi Santoso" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email (Login) <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Misal: budi@sistem.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Hak Akses (Role) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <select name="role" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm appearance-none">
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staf Gudang</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-info-circle mr-1"></i> Administrator memiliki akses penuh. Staf hanya dapat mengajukan permintaan dan memindai QR.</p>
            </div>

            <div class="border-t border-slate-100 pt-6 mb-6">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-800">Pengaturan Sandi</h3>
                    <p class="text-xs text-slate-500">Buat kata sandi awal untuk pengguna ini.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8">
                <button type="submit" class="flex-1 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex justify-center items-center gap-2">
                    <i class="fas fa-user-plus"></i> Tambahkan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
