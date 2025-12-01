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
        Schema::create('section_subjects', function (Blueprint $table) {
            $table->softDeletes();
            $table->id();

            // changed: referenced 'class_sections' (not 'sections')
            $table->foreignId('section_id')
                ->constrained('class_sections')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->unique(
                ['section_id', 'subject_id'],
                'unique_section_subject'
            );

            $table->timestamps();

            $table->index('section_id');
            $table->index('subject_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_subjects');
    }
};
