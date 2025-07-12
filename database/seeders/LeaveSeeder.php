<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::where('status', 'Active')->get();
        $leaveTypes = ['Paid Leave', 'Sick Leave', 'Casual Leave', 'Unpaid Leave'];
        $statuses = ['Pending', 'Approved', 'Rejected'];
        $reasons = [
            'Personal emergency',
            'Family function',
            'Medical appointment',
            'Vacation',
            'Wedding ceremony',
            'Health issues',
            'Mental health day',
            'Family vacation',
            'Religious festival',
            'Work from home'
        ];

        foreach ($employees as $employee) {
            // Create between 2 and 8 leave records for each employee
            for ($i = 0; $i < rand(2, 8); $i++) {
                $startDate = Carbon::instance(fake()->dateTimeBetween('-12 months', '+2 months'));
                $duration = rand(1, 15);
                $endDate = $startDate->copy()->addDays($duration - 1);

                Leave::create([
                    'employee_id' => $employee->id,
                    'leave_type' => fake()->randomElement($leaveTypes),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'duration_in_days' => $duration,
                    'reason' => fake()->randomElement($reasons),
                    'status' => fake()->randomElement($statuses),
                ]);
            }
        }
    }
}
