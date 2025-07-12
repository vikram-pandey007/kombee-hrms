<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Link to employees table

            // Payroll Details from requirements
            $table->string('month'); // e.g., "2024-07"
            $table->decimal('base_salary', 10, 2);
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('net_pay', 10, 2);
            $table->string('status')->default('Unpaid'); // Paid / Unpaid

            $table->timestamps();

            // Ensure an employee has only one payroll per month
            $table->unique(['employee_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
