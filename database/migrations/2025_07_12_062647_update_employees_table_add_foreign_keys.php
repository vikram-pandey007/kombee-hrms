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
        Schema::table('employees', function (Blueprint $table) {
            // Add foreign key columns
            $table->foreignId('department_id')->nullable()->after('phone')->constrained()->onDelete('set null');
            $table->foreignId('designation_id')->nullable()->after('department_id')->constrained()->onDelete('set null');

            // Drop old string columns
            $table->dropColumn(['department', 'designation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add back old columns
            $table->string('department')->after('phone');
            $table->string('designation')->after('department');

            // Drop foreign key columns
            $table->dropForeign(['department_id']);
            $table->dropForeign(['designation_id']);
            $table->dropColumn(['department_id', 'designation_id']);
        });
    }
};
