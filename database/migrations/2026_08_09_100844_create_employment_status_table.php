<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employment_status', function (Blueprint $table) {

            $table->id();

            // Employee
            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Plantilla Item
            $table->foreignId('plantilla_db_id')
                ->nullable()
                ->constrained('plantilla_db')
                ->nullOnDelete();

            // Assigned School
            $table->foreignId('school_db_id')
                ->nullable()
                ->constrained('school_db')
                ->nullOnDelete();

            // Employment Information
            $table->date('date_of_original_appointment')->nullable();
            $table->date('date_of_last_promotion')->nullable();

            $table->string('employment_status', 100)->nullable();
            $table->string('warm_body_status', 100)->nullable();
            $table->string('nature_of_work', 100)->nullable();
            $table->string('source_of_fund', 100)->nullable();

            // Salary
            $table->decimal('monthly_salary', 12, 2)->nullable();

            // Contract
            $table->string('contract_duration', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employment_status');
    }
};