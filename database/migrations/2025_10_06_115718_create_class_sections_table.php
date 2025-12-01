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
            $table->id('class_section_id');
            $table->string('section_name'); // e.g. "BSIT 3A" or "EDUC 1B"
            $table->foreignId('course_id')->constrained('courses', 'course_id')->cascadeOnDelete();
            $table->string('academic_year');
            $table->string('semester');
            $table->timestamps();
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