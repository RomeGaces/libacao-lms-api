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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id('class_schedule_id');

            // Relationships
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->cascadeOnDelete();
            $table->foreignId('professor_id')->nullable()->constrained('professors', 'professor_id')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms', 'room_id')->nullOnDelete();

            // Schedule details
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('class_section_id')->nullable()->constrained('class_sections', 'class_section_id')->nullOnDelete();
            $table->enum('status', ['pending', 'finalized'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};