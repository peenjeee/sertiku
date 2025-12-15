<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all();
foreach ($users as $user) {
    if ($user->is_admin) {
        $user->account_type = 'admin';
    } elseif (strpos($user->email, 'lembaga') !== false) {
        $user->account_type = 'lembaga';
    } else {
        $user->account_type = 'pengguna';
    }
    $user->save();
    echo "Updated {$user->email} to {$user->account_type}\n";
}

echo "All users updated.\n";
