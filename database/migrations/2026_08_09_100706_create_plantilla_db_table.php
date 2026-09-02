<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantilla_db', function (Blueprint $table) {

            $table->id();

            $table->string('item_number', 100)->nullable();
            $table->string('item_from', 100)->nullable();
            $table->string('item_from_school_level', 100)->nullable();

            $table->string('position_title', 150);
            $table->string('salary_grade', 10)->nullable();

            $table->string('area_code', 50)->nullable();
            $table->string('area_type', 100)->nullable();

            $table->string('plantilla_level', 100)->nullable();
            $table->string('pppa_attribution', 150)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantilla_db');
    }
};