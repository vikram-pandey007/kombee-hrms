<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('employee_id')->unique(); // Auto-generated custom ID
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Link to users table

            // Basic Details from requirements
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('department');
            $table->string('designation');
            $table->date('joining_date');
            $table->string('status')->default('Active'); // Active/Inactive

            // For Payroll
            $table->decimal('fixed_salary', 10, 2)->default(0.00);

            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
