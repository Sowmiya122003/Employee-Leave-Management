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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('leave_type_name')->unique();
            $table->foreignID('created_by')->constrained('users');
            $table->unsignedInteger('per_month');
            $table->unsignedInteger('per_year');
            $table->unsignedInteger('monthly_carry_forward');
            $table->unsignedInteger('yearly_carry_forward');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
