<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@eatera.com'],
            [
                'name' => 'Admin Eatera',
                'password' => Hash::make('admin2025'),
                'role' => 'admin',
            ]
        );

        echo "✓ Admin user ready (admin@eatera.com / admin2025)\n";
    }
}
