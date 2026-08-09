<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eligibility', function (Blueprint $table) {

            // Remove old relationship
            $table->dropForeign(['users_id']);
            $table->dropColumn('users_id');

            // Add correct relationship
            $table->foreignId('basic_information_id')
                ->after('id')
                ->constrained('basic_information')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('eligibility', function (Blueprint $table) {

            // Remove new relationship
            $table->dropForeign(['basic_information_id']);
            $table->dropColumn('basic_information_id');

            // Restore old relationship
            $table->foreignId('users_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};