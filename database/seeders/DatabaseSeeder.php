<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the seeders in the correct order
        $this->call([
            UserSeeder::class,
            EmployeeSeeder::class,
            LeaveSeeder::class,
            PayrollSeeder::class,
        ]);
    }
}
