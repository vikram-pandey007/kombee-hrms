<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            ['name' => 'Software Developer', 'description' => 'Full Stack Developer'],
            ['name' => 'Senior Developer', 'description' => 'Senior Software Developer'],
            ['name' => 'Team Lead', 'description' => 'Development Team Lead'],
            ['name' => 'Project Manager', 'description' => 'Project Management'],
            ['name' => 'HR Manager', 'description' => 'Human Resources Manager'],
            ['name' => 'HR Executive', 'description' => 'HR Executive'],
            ['name' => 'Accountant', 'description' => 'Finance and Accounting'],
            ['name' => 'Finance Manager', 'description' => 'Finance Manager'],
            ['name' => 'Marketing Executive', 'description' => 'Marketing and Sales'],
            ['name' => 'Sales Manager', 'description' => 'Sales Management'],
            ['name' => 'Customer Support Executive', 'description' => 'Customer Service'],
            ['name' => 'Operations Manager', 'description' => 'Operations Management'],
        ];

        foreach ($designations as $designation) {
            Designation::create($designation);
        }
    }
}
