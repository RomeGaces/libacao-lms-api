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
            $table->id('subject_id');
            $table->foreignId('course_id')
                ->constrained('courses', 'course_id')
                ->cascadeOnDelete();
            $table->string('subject_code')->unique();
            $table->string('subject_name');
            $table->text('description')->nullable();
            $table->integer('units')->default(3);
            $table->enum('semester', ['1st', '2nd', 'Summer'])->nullable();
            $table->integer('year_level')->nullable(); // 1–4
            $table->timestamps();
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
