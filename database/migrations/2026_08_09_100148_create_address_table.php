<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address', function (Blueprint $table) {

            $table->id();

            $table->foreignId('basic_information_id')
                ->constrained('basic_information')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('type', [
                'permanent',
                'residential',
            ]);

            $table->text('street')->nullable();
            $table->text('brgy')->nullable();
            $table->text('subd_village')->nullable();
            $table->string('municipality_city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_postal', 20)->nullable();

            $table->timestamps();

            // One permanent and one residential address per employee
            $table->unique([
                'basic_information_id',
                'type'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};