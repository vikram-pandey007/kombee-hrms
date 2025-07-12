<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'duration_in_days',
        'reason',
        'status',
        'approver_comment',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public static function rules($leaveId = null)
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:Casual Leave,Sick Leave,Annual Leave,Personal Leave,Other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration_in_days' => 'required|numeric|min:0.5|max:365',
            'reason' => 'required|string|min:10|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
            'approver_comment' => 'nullable|string|max:500',
        ];
    }

    public static function messages()
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'leave_type.required' => 'Please select a leave type.',
            'leave_type.in' => 'Please select a valid leave type.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'duration_in_days.required' => 'Duration is required.',
            'duration_in_days.numeric' => 'Duration must be a valid number.',
            'duration_in_days.min' => 'Duration must be at least 0.5 days.',
            'duration_in_days.max' => 'Duration cannot exceed 365 days.',
            'reason.required' => 'Reason is required.',
            'reason.min' => 'Reason must be at least 10 characters.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be Pending, Approved, or Rejected.',
            'approver_comment.max' => 'Approver comment cannot exceed 500 characters.',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match (strtolower($this->status)) {
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }
}
