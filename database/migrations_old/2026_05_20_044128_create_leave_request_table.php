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
        Schema::create('leave_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_of_leave_id')->constrained('leave_type');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('total_days');
            $table->timestamp('applied_at');
            $table->text('reason');
            $table->enum('status',['approved','pending','rejected','cancelled']);
            $table->foreignId('action_taken_by')->constrained('users');
            $table->timestamp('action_time')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('attachments')->nullable();
            $table->integer('lop_days');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_request');
    }
};
