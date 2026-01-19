<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Regular - User Biasa
        User::create([
            'name' => 'Ajt User',
            'email' => 'ajt@gmail.com',
            'password' => Hash::make('ajt2025'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Admin User - User Admin
        $admin = User::create([
            'name' => 'Admin EATERA',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin2025'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create sample articles
        Article::create([
            'title' => '7 Tips Makan Sehat untuk Tubuh yang Ideal',
            'content' => 'Makan sehat bukan hanya tentang diet ketat, tetapi tentang memilih makanan yang tepat untuk tubuh Anda. Berikut adalah beberapa tips praktis untuk memulai perjalanan hidup sehat Anda.',
            'status' => 'published',
            'admin_id' => $admin->id,
        ]);

        Article::create([
            'title' => 'Manfaat Olahraga Teratur untuk Kesehatan Mental',
            'content' => 'Selain manfaat fisik, olahraga ternyata memiliki dampak positif yang signifikan terhadap kesehatan mental kita. Mari kita pelajari lebih dalam tentang hubungan antara aktivitas fisik dan kesejahteraan mental.',
            'status' => 'published',
            'admin_id' => $admin->id,
        ]);

        Article::create([
            'title' => 'Panduan Lengkap Nutrisi untuk Atlet',
            'content' => 'Atlet membutuhkan nutrisi yang tepat untuk memaksimalkan performa mereka. Artikel ini akan membahas kebutuhan nutrisi khusus, timing makan, dan suplemen yang tepat.',
            'status' => 'draft',
            'admin_id' => $admin->id,
        ]);

        // Uncomment untuk generate 10 user random
        // User::factory(10)->create();
    }
}
