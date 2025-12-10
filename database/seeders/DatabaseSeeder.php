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
                'name'              => 'Admin SertiKu',
                'password'          => Hash::make('Admin123!'),
                'account_type'      => 'personal',
                'profile_completed' => true,
                'country'           => 'Indonesia',
            ]
        );

        // Lembaga
        User::updateOrCreate(
            ['email' => 'lembaga@sertiku.my.id'],
            [
                'name'              => 'Lembaga SertiKu',
                'password'          => Hash::make('Lembaga123!'),
                'account_type'      => 'institution',
                'profile_completed' => true,
                'institution_name'  => 'Lembaga SertiKu',
                'institution_type'  => 'company',
                'city'              => 'Sleman',
                'country'           => 'Indonesia',
                'admin_name'        => 'Admin Lembaga',
            ]
        );

        // User biasa
        User::updateOrCreate(
            ['email' => 'user@sertiku.my.id'],
            [
                'name'              => 'Pengguna Umum',
                'password'          => Hash::make('User123!'),
                'account_type'      => 'personal',
                'profile_completed' => true,
                'country'           => 'Indonesia',
            ]
        );

        // Run other seeders
        $this->call([
            PackageSeeder::class,
        ]);
    }
}
