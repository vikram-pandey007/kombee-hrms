<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'email',
        'phone',
        'department',
        'designation',
        'joining_date',
        'status',
        'fixed_salary',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'fixed_salary' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(Leave::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
