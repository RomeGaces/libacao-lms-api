<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_schedules', function (Blueprint $table) {
            $table->softDeletes();
            $table->id();

            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('professor_id')->nullable()->constrained('professors')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('class_section_id')
                ->nullable()
                ->constrained('class_sections', 'id')
                ->nullOnDelete();

            $table->string('day_of_week', 10);
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', ['Pending', 'Reviewed', 'Approved', 'Discarded'])->default('Pending');
            $table->enum('generated_by', ['System', 'User'])->default('system');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['section_id', 'day_of_week', 'start_time'], 'idx_draft_section_time');
            $table->index(['professor_id', 'day_of_week', 'start_time'], 'idx_draft_prof_time');
            $table->index(['room_id', 'day_of_week', 'start_time'], 'idx_draft_room_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_schedules');
    }
};
