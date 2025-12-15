<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UpdateUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Update or create admin
        User::updateOrCreate(
            ['email' => 'admin@sertiku.web.id'],
            [
                'name'              => 'Admin SertiKu',
                'password'          => Hash::make('Admin123'),
                'is_admin'          => true,
                'account_type'      => 'pengguna',
                'profile_completed' => true,
            ]
        );

        // Also set any existing admin users
        User::where('email', 'like', '%admin%')
            ->where('email', '!=', 'admin@sertiku.web.id')
            ->update(['is_admin' => false]);

        // Update lembaga email
        User::where('email', 'lembaga@sertiku.my.id')
            ->update(['email' => 'lembaga@sertiku.web.id']);

        // Update user email
        User::where('email', 'user@sertiku.my.id')
            ->update(['email' => 'user@sertiku.web.id']);

        echo "Users updated successfully!\n";
        echo "Admin: admin@sertiku.web.id / Admin123\n";
    }
}
