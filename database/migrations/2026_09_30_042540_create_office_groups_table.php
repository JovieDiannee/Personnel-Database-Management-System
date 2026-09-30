<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_groups', function (Blueprint $table) {

            $table->id();

            // Examples: OSDS, CID, SGOD
            $table->string('code', 20)->unique();

            // Complete office/division name
            $table->string('name', 150);

            // Optional description
            $table->text('description')->nullable();

            // Controls display order
            $table->unsignedInteger('sort_order')->default(0);

            // Allows office groups to be deactivated
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_groups');
    }
};