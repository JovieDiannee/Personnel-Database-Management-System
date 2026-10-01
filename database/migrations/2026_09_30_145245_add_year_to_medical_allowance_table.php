<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Add Year
        |--------------------------------------------------------------------------
        */

        Schema::table('medical_allowance', function (Blueprint $table) {
            $table->year('year')
                ->nullable()
                ->after('users_id');
        });


        /*
        |--------------------------------------------------------------------------
        | Existing Records Are 2026 Records
        |--------------------------------------------------------------------------
        */

        DB::table('medical_allowance')
            ->whereNull('year')
            ->update([
                'year' => 2026
            ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate User + Year Records
        |--------------------------------------------------------------------------
        */

        Schema::table('medical_allowance', function (Blueprint $table) {
            $table->unique(
                ['users_id', 'year'],
                'medical_allowance_user_year_unique'
            );
        });
    }


    public function down(): void
    {
        Schema::table('medical_allowance', function (Blueprint $table) {

            $table->dropUnique(
                'medical_allowance_user_year_unique'
            );

            $table->dropColumn('year');
        });
    }
};