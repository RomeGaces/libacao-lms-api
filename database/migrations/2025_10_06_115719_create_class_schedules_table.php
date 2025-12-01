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
            $table->softDeletes();
            $table->id();

            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('class_section_id')->nullable()->constrained('class_sections')->nullOnDelete();
            $table->foreignId('professor_id')->nullable()->constrained('professors')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();

            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'])->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            
            $table->enum('status', ['Pending', 'Finalized'])->default('Pending');
            $table->timestamps();

            $table->index('class_section_id');
            $table->index('subject_id');
            $table->index('professor_id');
            $table->index('room_id');
            $table->index('day_of_week');

            $table->index(['professor_id', 'day_of_week', 'start_time'], 'idx_professor_time');
            $table->index(['room_id', 'day_of_week', 'start_time'], 'idx_room_time');
            $table->index(['class_section_id', 'day_of_week', 'start_time'], 'idx_section_time');
            $table->index(['class_section_id', 'day_of_week'], 'idx_section_day');
            $table->index(['day_of_week', 'start_time', 'end_time'], 'idx_day_time_range');
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
