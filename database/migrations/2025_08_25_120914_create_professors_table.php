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
        Schema::create('professors', function (Blueprint $table) {
            $table->id('professor_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->enum('gender', ['Male', 'Female']);
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('specialization')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('departments', 'department_id')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professors');
    }
};