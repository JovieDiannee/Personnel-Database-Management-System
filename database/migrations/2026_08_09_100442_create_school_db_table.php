<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_db', function (Blueprint $table) {

            $table->id();
            
            $table->string('school_id', 10);
            $table->string('school_name', 255);
            $table->string('school_area', 100)->nullable();
            $table->string('legislative_district', 100)->nullable();
            $table->string('school_district', 150)->nullable();
            $table->string('school_municipality', 150)->nullable();
            $table->string('school_sector', 100)->nullable();
            $table->string('school_curricular_offering', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_db');
    }
};