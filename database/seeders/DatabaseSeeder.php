<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@sertiku.my.id'],
            [
                'name'     => 'Admin SertiKu',
                'password' => Hash::make('Admin123!'),
                // kalau punya kolom role:
                // 'role' => 'admin',
            ]
        );

        // Lembaga
        User::updateOrCreate(
            ['email' => 'lembaga@sertiku.my.id'],
            [
                'name'     => 'Akun Lembaga',
                'password' => Hash::make('Lembaga123!'),
                // 'role' => 'lembaga',
            ]
        );

        // User biasa
        User::updateOrCreate(
            ['email' => 'user@sertiku.my.id'],
            [
                'name'     => 'Pengguna Umum',
                'password' => Hash::make('User123!'),
                // 'role' => 'user',
            ]
        );
    }
}
