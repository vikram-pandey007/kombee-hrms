<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get department and designation IDs
        $itDept = Department::where('name', 'Information Technology')->first();
        $hrDept = Department::where('name', 'Human Resources')->first();
        $financeDept = Department::where('name', 'Finance')->first();
        $marketingDept = Department::where('name', 'Marketing')->first();

        $developer = Designation::where('name', 'Software Developer')->first();
        $seniorDev = Designation::where('name', 'Senior Developer')->first();
        $hrManager = Designation::where('name', 'HR Manager')->first();
        $accountant = Designation::where('name', 'Accountant')->first();
        $marketingExec = Designation::where('name', 'Marketing Executive')->first();

        $employees = [
            [
                'employee_id' => 'EMP-0001',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+91-9876543210',
                'department_id' => $itDept->id,
                'designation_id' => $seniorDev->id,
                'joining_date' => '2023-01-15',
                'fixed_salary' => 75000.00,
                'status' => 'Active',
            ],
            [
                'employee_id' => 'EMP-0002',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+91-9876543211',
                'department_id' => $hrDept->id,
                'designation_id' => $hrManager->id,
                'joining_date' => '2023-02-20',
                'fixed_salary' => 65000.00,
                'status' => 'Active',
            ],
            [
                'employee_id' => 'EMP-0003',
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@example.com',
                'phone' => '+91-9876543212',
                'department_id' => $itDept->id,
                'designation_id' => $developer->id,
                'joining_date' => '2023-03-10',
                'fixed_salary' => 55000.00,
                'status' => 'Active',
            ],
            [
                'employee_id' => 'EMP-0004',
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@example.com',
                'phone' => '+91-9876543213',
                'department_id' => $financeDept->id,
                'designation_id' => $accountant->id,
                'joining_date' => '2023-04-05',
                'fixed_salary' => 45000.00,
                'status' => 'Active',
            ],
            [
                'employee_id' => 'EMP-0005',
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'phone' => '+91-9876543214',
                'department_id' => $marketingDept->id,
                'designation_id' => $marketingExec->id,
                'joining_date' => '2023-05-12',
                'fixed_salary' => 50000.00,
                'status' => 'Active',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
