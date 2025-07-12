<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hrms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create additional test users
        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@hrms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Finance Manager',
            'email' => 'finance@hrms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
