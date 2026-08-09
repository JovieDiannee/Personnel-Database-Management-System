<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {

            $table->id();

            // School
            $table->foreignId('school_db_id')
                ->constrained('school_db')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // School Year
            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Grade Level
            $table->foreignId('grade_level_id')
                ->constrained('grade_levels')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Number of enrolled learners
            $table->unsignedInteger('enrollment_count')
                ->default(0);

            $table->timestamps();

            // Prevent duplicate enrollment records
            $table->unique([
                'school_db_id',
                'school_year_id',
                'grade_level_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};