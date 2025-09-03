<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'google_id' => 'admin_google_id_123',
            'avatar' => 'https://via.placeholder.com/150',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'last_activity' => now(),
        ]);

        // Create member user
        User::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'google_id' => 'member_google_id_456',
            'avatar' => 'https://via.placeholder.com/150',
            'password' => Hash::make('password'),
            'role' => 'member',
            'last_activity' => now(),
        ]);
    }
}
