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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('employee_number')->unique()->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary_rate', 10, 2)->nullable();
            $table->string('employment_status')->default('active'); // active, on_leave, terminated
            // Government Records
            $table->string('sss_number')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
