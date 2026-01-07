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
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_period'); // e.g., "January 2024"
            $table->date('period_start');
            $table->date('period_end');
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('draft'); // draft, processed, finalized
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
