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
        Schema::create('subjects', function (Blueprint $table) {
            $table->softDeletes();
            $table->id();
            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();
            $table->string('subject_code')->unique();
            $table->string('subject_name');
            $table->unsignedTinyInteger('units')->default(3);
            $table->enum('type', ['Lecture', 'Laboratory'])->default('Lecture');
            $table->unsignedTinyInteger('hours_per_week')->default(3);
            $table->text('description')->nullable();

            $table->enum('semester', ['1st', '2nd', 'Summer'])->nullable();
            $table->integer('year_level')->nullable(); // 1–4

            // Self-referencing prerequisite
            $table->foreignId('subject_prerequisite_id')
                ->nullable()
                ->constrained('subjects')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('course_id');
            $table->index('year_level');
            $table->index('semester');
            $table->index('subject_prerequisite_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
