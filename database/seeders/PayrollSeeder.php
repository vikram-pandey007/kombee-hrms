<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('status', 'Active')->get();
        $statuses = ['Paid', 'Unpaid'];

        foreach ($employees as $employee) {
            // Generate payroll for the last 6 months
            for ($i = 0; $i < 6; $i++) {
                $month = Carbon::now()->subMonths($i);
                $monthString = $month->format('Y-m');

                // Calculate deductions based on unpaid leaves
                $unpaidLeaveDays = Leave::where('employee_id', $employee->id)
                    ->where('leave_type', 'Unpaid Leave')
                    ->where('status', 'Approved')
                    ->whereYear('start_date', $month->year)
                    ->whereMonth('start_date', $month->month)
                    ->sum('duration_in_days');

                $dailyRate = $employee->fixed_salary / 30;
                $deductions = $dailyRate * $unpaidLeaveDays;
                $netPay = $employee->fixed_salary - $deductions;

                Payroll::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'month' => $monthString,
                    ],
                    [
                        'base_salary' => $employee->fixed_salary,
                        'deductions' => $deductions,
                        'net_pay' => $netPay,
                        'status' => ($i == 0) ? fake()->randomElement($statuses) : 'Paid',
                    ]
                );
            }
        }
    }
}
