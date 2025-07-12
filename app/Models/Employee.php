<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'phone',
        'department_id',
        'designation_id',
        'joining_date',
        'fixed_salary',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'fixed_salary' => 'decimal:2',
    ];

    public static function rules($employeeId = null)
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees')->ignore($employeeId),
            ],
            'phone' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'joining_date' => 'required|date|before_or_equal:today',
            'fixed_salary' => 'required|numeric|min:0|max:999999.99'
        ];
    }

    public static function messages()
    {
        return [
            'name.required' => 'Employee name is required.',
            'name.min' => 'Employee name must be at least 3 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'Selected department is invalid.',
            'designation_id.required' => 'Please select a designation.',
            'designation_id.exists' => 'Selected designation is invalid.',
            'joining_date.required' => 'Joining date is required.',
            'joining_date.before_or_equal' => 'Joining date cannot be in the future.',
            'fixed_salary.required' => 'Fixed salary is required.',
            'fixed_salary.numeric' => 'Fixed salary must be a valid number.',
            'fixed_salary.min' => 'Fixed salary cannot be negative.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either Active or Inactive.',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function getDepartmentNameAttribute()
    {
        return $this->department ? $this->department->name : 'N/A';
    }

    public function getDesignationNameAttribute()
    {
        return $this->designation ? $this->designation->name : 'N/A';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }
}
