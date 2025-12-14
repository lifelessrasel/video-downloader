<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'lifelessrasel@gmail.com'],
            [
                'name' => 'Admin Rasel',
                'password' => Hash::make('password'), // You should change this later
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
        
        // Ensure no other users exist if we want strict mode (optional, but requested "no registration enabled")
        // Actually, we disable registration via Routes, but existing users remain.
    }
}
