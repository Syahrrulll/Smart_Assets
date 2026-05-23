<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT USER SUPER ADMIN (Akses Penuh)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'admin',
        ]);

        // 2. BUAT USER PETUGAS (Untuk Tes QR Strict Mode)
        User::create([
            'name' => 'Petugas Gudang',
            'email' => 'staf@gudang.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'user',
        ]);

        
    }
}
