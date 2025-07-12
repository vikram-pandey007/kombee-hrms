<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Enhanced deduction tracking
            $table->decimal('leave_deductions', 10, 2)->default(0.00)->after('deductions'); // Deductions from unpaid leaves
            $table->decimal('other_deductions', 10, 2)->default(0.00)->after('leave_deductions'); // Other deductions (tax, insurance, etc.)
            $table->text('deduction_notes')->nullable()->after('other_deductions'); // Notes about deductions
            $table->text('remarks')->nullable()->after('deduction_notes'); // General remarks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['leave_deductions', 'other_deductions', 'deduction_notes', 'remarks']);
        });
    }
};
