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
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('current_school_year_id')
                ->nullable()
                ->constrained('school_years')
                ->nullOnDelete();

            $table->foreignId('current_semester_id')
                ->nullable()
                ->constrained('semesters')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
