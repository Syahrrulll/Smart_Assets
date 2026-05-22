@extends('layouts.app')

@section('title', 'Edit Pengguna | Smart Asset Management')
@section('header_title', 'Edit Akun Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center">
        <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition-all mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold font-outfit text-slate-800">{{ $user->name }}</h2>
            <p class="text-slate-500 text-sm">Perbarui profil, hak akses, atau kata sandi pengguna.</p>
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
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email (Login)</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Hak Akses (Role)</label>
                <div class="relative">
                    <select name="role" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm appearance-none">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staf Gudang</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-info-circle mr-1"></i> Administrator memiliki akses penuh. Staf hanya dapat mengajukan permintaan dan memindai QR.</p>
            </div>

            <div class="border-t border-slate-100 pt-6 mb-6">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-800">Ubah Kata Sandi</h3>
                    <p class="text-xs text-slate-500">Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8">
                <button type="submit" class="flex-1 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-600/20 transition-all flex justify-center items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
