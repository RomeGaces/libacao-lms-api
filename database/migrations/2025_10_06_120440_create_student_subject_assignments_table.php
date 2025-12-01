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
        Schema::create('student_subject_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->cascadeOnDelete();
            $table->foreignId('class_section_id')->constrained('class_sections', 'class_section_id')->cascadeOnDelete();
            $table->enum('status', ['enrolled','dropped', 'completed'])->default('enrolled'); 
            $table->string('grade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subject_assignments');
    }
};
