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
        Schema::create('class_sections', function (Blueprint $table) {
            $table->softDeletes();
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();

            // Academic period
            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            $table->foreignId('semester_id')
                ->constrained('semesters')
                ->cascadeOnDelete();

            // Metadata
            $table->unsignedTinyInteger('year_level');   // 1-4
            $table->string('section_name');              // e.g., "II-IT2", "I-CS1"

            $table->unsignedSmallInteger('capacity')->nullable();

            // Prevent duplicates
            $table->unique(
                ['course_id', 'year_level', 'school_year_id', 'semester_id', 'section_name'],
                'unique_section_per_period'
            );

            $table->timestamps();

            $table->index(['course_id', 'year_level']);
            $table->index('school_year_id');
            $table->index('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_sections');
    }
};
