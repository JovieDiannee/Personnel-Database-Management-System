<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_units', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Office Group
            |--------------------------------------------------------------------------
            |
            | OSDS
            | CID
            | SGOD
            |
            */

            $table->foreignId('office_group_id')
                ->constrained('office_groups')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Parent Unit
            |--------------------------------------------------------------------------
            |
            | Allows nested office units.
            |
            | Example:
            |
            | Admin
            |   └── Personnel Unit
            |         ├── Personnel Proper
            |         ├── Personnel Files
            |         ├── Payroll
            |         └── Remittance
            |
            */

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('office_units')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Office Unit Information
            |--------------------------------------------------------------------------
            */

            $table->string('code', 50)->nullable();

            $table->string('name', 150);

            $table->string('short_name', 100)->nullable();


            /*
            |--------------------------------------------------------------------------
            | Unit Type
            |--------------------------------------------------------------------------
            |
            | Examples:
            | Office
            | Division
            | Unit
            | Section
            |
            */

            $table->string('unit_type', 50)
                ->default('Unit');


            /*
            |--------------------------------------------------------------------------
            | Display Order
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('sort_order')
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('name');
            $table->index('code');
            $table->index('is_active');
            $table->index('sort_order');

            $table->unique(
                ['office_group_id', 'code'],
                'office_units_group_code_unique'
            );
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('office_units');
    }
};