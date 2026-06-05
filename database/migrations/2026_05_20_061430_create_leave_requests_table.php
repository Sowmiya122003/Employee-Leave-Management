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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('type_of_leave_id')->nullable()->constrained('leave_types')->nullOnDelete();
            $table->date('from_date');
            $table->date('to_date');
            $table->decimal('requested_leaves',5,2)->default(0);
            $table->decimal('approved_leaves',5,2)->default(0);
            $table->decimal('unpaid_leaves', 5, 2)->default(0);
            $table->timestamp('applied_at')->useCurrent();
            $table->text('reason');
            $table->enum('status',['approved','pending','rejected','cancelled'])->default('pending');
            $table->foreignId('action_taken_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('action_time')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('attachments')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
