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
        Schema::create('leave_monthly_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('type_of_leave_id')->nullable()->constrained('leave_types')->nullOnDelete();
            $table->year('company_year');
            $table->tinyInteger('month');
            $table->decimal('allocated_leaves', 5, 2)->default(0);
            $table->decimal('used_leaves', 5, 2)->default(0);
            $table->decimal('carry_forward_days', 5, 2)->default(0);
            $table->decimal('unpaid_leaves', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'type_of_leave_id', 'company_year', 'month'], 'leave_monthly_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_monthly_balances');
    }
};
