<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'base_salary',
        'deductions',
        'net_pay',
        'status',
        'leave_deductions',
        'other_deductions',
        'deduction_notes',
        'remarks',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'leave_deductions' => 'decimal:2',
        'other_deductions' => 'decimal:2',
    ];

    public static function rules($payrollId = null)
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string|max:7',
            'base_salary' => 'required|numeric|min:0|max:999999.99',
            'deductions' => 'required|numeric|min:0|max:999999.99',
            'net_pay' => 'required|numeric|min:0|max:999999.99',
            'status' => 'required|in:Pending,Paid,Cancelled',
            'leave_deductions' => 'required|numeric|min:0|max:999999.99',
            'other_deductions' => 'required|numeric|min:0|max:999999.99',
            'deduction_notes' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
        ];
    }

    public static function messages()
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'month.required' => 'Payroll month is required.',
            'month.string' => 'Month must be a valid string.',
            'base_salary.required' => 'Base salary is required.',
            'base_salary.numeric' => 'Base salary must be a valid number.',
            'base_salary.min' => 'Base salary cannot be negative.',
            'deductions.required' => 'Deductions are required.',
            'deductions.numeric' => 'Deductions must be a valid number.',
            'deductions.min' => 'Deductions cannot be negative.',
            'net_pay.required' => 'Net pay is required.',
            'net_pay.numeric' => 'Net pay must be a valid number.',
            'net_pay.min' => 'Net pay cannot be negative.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be Pending, Paid, or Cancelled.',
            'leave_deductions.required' => 'Leave deductions are required.',
            'leave_deductions.numeric' => 'Leave deductions must be a valid number.',
            'leave_deductions.min' => 'Leave deductions cannot be negative.',
            'other_deductions.required' => 'Other deductions are required.',
            'other_deductions.numeric' => 'Other deductions must be a valid number.',
            'other_deductions.min' => 'Other deductions cannot be negative.',
            'deduction_notes.max' => 'Deduction notes cannot exceed 500 characters.',
            'remarks.max' => 'Remarks cannot exceed 500 characters.',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getNetSalaryAttribute()
    {
        return $this->net_pay;
    }

    public function getTotalDeductionsAttribute()
    {
        return $this->leave_deductions + $this->other_deductions;
    }

    public function getFormattedMonthAttribute()
    {
        return \Carbon\Carbon::parse($this->month . '-01')->format('F Y');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'Paid');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'Cancelled');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    public function scopeByDateRange($query, $startMonth, $endMonth)
    {
        return $query->whereBetween('month', [$startMonth, $endMonth]);
    }
}
