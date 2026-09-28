<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('add_personnel_requests', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Request Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */

            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('extension_name', 20)->nullable();

            $table->string('email')->index();

            $table->string('sex', 20)->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();

            $table->string('mobile_number', 30)->nullable();
            $table->string('specialization', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Employment Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('school_db_id')
                ->nullable()
                ->constrained('school_db')
                ->nullOnDelete();

            $table->foreignId('plantilla_db_id')
                ->nullable()
                ->constrained('plantilla_db')
                ->nullOnDelete();

            $table->date('date_of_original_appointment')->nullable();
            $table->date('date_of_last_promotion')->nullable();

            $table->string('employment_status', 100)->nullable();
            $table->string('warm_body_status', 100)->nullable();
            $table->string('nature_of_work', 100)->nullable();
            $table->string('source_of_fund', 100)->nullable();

            $table->decimal('monthly_salary', 12, 2)->nullable();
            $table->string('contract_duration', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Approval Information
            |--------------------------------------------------------------------------
            */

            $table->string('status', 30)
                ->default('pending')
                ->index();

            $table->text('request_remarks')->nullable();
            $table->text('review_remarks')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Created Personnel Account
            |--------------------------------------------------------------------------
            |
            | Populated only after approval.
            |
            */

            $table->foreignId('created_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_personnel_requests');
    }
};