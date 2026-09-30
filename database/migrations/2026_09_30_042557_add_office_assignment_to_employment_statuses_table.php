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
        Schema::table('employment_status', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Office Assignment
            |--------------------------------------------------------------------------
            |
            | NULL = Not assigned to a Division Office unit
            |
            | For School Personnel:
            | school_db_id   = has value
            | office_unit_id = NULL
            |
            | For Division Office Personnel:
            | school_db_id   = NULL
            | office_unit_id = has value
            |
            */

            $table->foreignId('office_unit_id')
                ->nullable()
                ->after('school_db_id')
                ->constrained('office_units')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employment_status', function (Blueprint $table) {

            $table->dropForeign([
                'office_unit_id'
            ]);

            $table->dropColumn('office_unit_id');
        });
    }
};