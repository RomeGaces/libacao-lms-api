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
            $table->softDeletes();
            $table->id();
            
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('class_section_id')->constrained('class_sections')->cascadeOnDelete();
            $table->enum('status', ['Enrolled', 'Dropped', 'Completed'])->default('Enrolled');
            $table->string('grade')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('subject_id');
            $table->index('class_section_id');
            $table->index('status');

            $table->index(['student_id', 'status'], 'idx_student_status');
            $table->index(['class_section_id', 'status'], 'idx_section_status');
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
