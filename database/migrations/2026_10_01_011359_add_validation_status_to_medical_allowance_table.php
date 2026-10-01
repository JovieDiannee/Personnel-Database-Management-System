<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_allowance', function (Blueprint $table) {

            $table->string('validation_status', 30)
                ->default('Pending')
                ->after('disbursement_status');

        });
    }

    public function down(): void
    {
        Schema::table('medical_allowance', function (Blueprint $table) {

            $table->dropColumn('validation_status');

        });
    }
};