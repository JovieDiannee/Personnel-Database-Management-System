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
        Schema::create('basic_information', function (Blueprint $table) {

            $table->id();

            // Relationship to users table
            $table->foreignId('users_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Personal Information
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('extension_name', 20)->nullable();

            $table->string('sex', 20)->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();

            $table->string('civil_status', 50)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('citizenship', 100)->nullable();
            $table->string('mode_of_citizenship', 50)->nullable();

            // Physical Information
            $table->decimal('height_m', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->string('blood_type', 10)->nullable();

            // Contact Information
            $table->string('mobile_number', 30)->nullable();
            $table->string('telephone_number', 30)->nullable();

            // Professional Information
            $table->string('specialization', 150)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_information');
    }
};