<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Master Admin (Super Admin)
        User::updateOrCreate(
            ['email' => 'master@sertiku.web.id'],
            [
                'name'              => 'Master SertiKu',
                'password'          => Hash::make('Master123'),
                'is_admin'          => true,
                'is_master'         => true,
                'account_type'      => 'admin',
                'profile_completed' => true,
                'country'           => 'Indonesia',
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@sertiku.web.id'],
            [
                'name'              => 'Admin SertiKu',
                'password'          => Hash::make('Admin123'),
                'is_admin'          => true,
                'is_master'         => false,
                'account_type'      => 'admin',
                'profile_completed' => true,
                'country'           => 'Indonesia',
            ]
        );

        // Lembaga
        User::updateOrCreate(
            ['email' => 'lembaga@sertiku.web.id'],
            [
                'name'              => 'Lembaga SertiKu',
                'password'          => Hash::make('lembaga123'),
                'is_admin'          => false,
                'is_master'         => false,
                'account_type'      => 'lembaga',
                'profile_completed' => true,
                'institution_name'  => 'Lembaga SertiKu',
                'institution_type'  => 'company',
                'city'              => 'Jakarta',
                'country'           => 'Indonesia',
                'admin_name'        => 'Admin Lembaga',
            ]
        );

        // User biasa (Pengguna)
        User::updateOrCreate(
            ['email' => 'user@sertiku.web.id'],
            [
                'name'              => 'User SertiKu',
                'password'          => Hash::make('user123'),
                'is_admin'          => false,
                'is_master'         => false,
                'account_type'      => 'pengguna',
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
