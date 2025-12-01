<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_schedules', function (Blueprint $table) {
            $table->id('draft_id');

            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->cascadeOnDelete();
            $table->foreignId('professor_id')->nullable()->constrained('professors','professor_id')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms', 'room_id')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('class_sections', 'class_section_id')->nullOnDelete();

            $table->string('day_of_week', 10);
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', ['pending', 'reviewed', 'approved', 'discarded'])->default('pending');
            $table->enum('generated_by', ['system', 'user'])->default('system');
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_schedules');
    }
};
