<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Information Technology', 'description' => 'IT and Software Development'],
            ['name' => 'Human Resources', 'description' => 'HR and Recruitment'],
            ['name' => 'Finance', 'description' => 'Finance and Accounting'],
            ['name' => 'Marketing', 'description' => 'Marketing and Sales'],
            ['name' => 'Operations', 'description' => 'Operations and Management'],
            ['name' => 'Customer Support', 'description' => 'Customer Service and Support'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
