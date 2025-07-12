<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getNetSalaryAttribute()
    {
        return $this->net_pay;
    }
}
