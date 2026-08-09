<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_allowance', function (Blueprint $table) {

            $table->id();

            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('mode_of_availment', 100);
            $table->string('disbursement_status', 50);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_allowance');
    }
};