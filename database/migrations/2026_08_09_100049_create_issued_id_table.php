<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issued_id', function (Blueprint $table) {

            $table->id();

            $table->foreignId('basic_information_id')
                ->unique()
                ->constrained('basic_information')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('umid_no')->nullable();
            $table->string('gsis_no')->nullable();
            $table->string('philsys_no')->nullable();
            $table->string('pagibig_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('philhealth_no')->nullable();
            $table->string('employee_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issued_id');
    }
};