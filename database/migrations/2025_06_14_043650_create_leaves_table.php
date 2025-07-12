<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Link to employees table

            // Leave Details from requirements
            $table->string('leave_type'); // Paid Leave, Casual Leave, Sick Leave
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_in_days');
            $table->text('reason');
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->text('approver_comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
