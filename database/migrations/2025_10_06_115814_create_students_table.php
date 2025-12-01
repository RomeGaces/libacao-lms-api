<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');

            // Basic student info
            $table->string('student_number')->unique(); // e.g., 2025-00123
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('birth_date')->nullable();

            // Contact / other details
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();

            $table->date('enrollment_date')->nullable();
            $table->enum('status', ['Enrolled', 'Dropped', 'Graduated', 'Inactive'])->default('Enrolled');

            $table->foreignId('course_id')
                  ->constrained('courses', 'course_id')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
