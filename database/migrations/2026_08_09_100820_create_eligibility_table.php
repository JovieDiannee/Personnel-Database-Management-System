<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eligibility', function (Blueprint $table) {

            $table->id();

            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('type', 150);
            $table->decimal('rating', 5, 2)->nullable();

            $table->date('exam_conferment_date')->nullable();
            $table->string('exam_conferment_place')->nullable();

            $table->string('license_number', 100)->nullable();
            $table->date('license_validity_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eligibility');
    }
};