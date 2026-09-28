<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Soft deletion: records remain in the users table.
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Deletion requests and audit history.
        Schema::create('employee_deletion_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('request_remarks');

            // pending, approved, or disapproved
            $table->string('status', 20)->default('pending');

            // request = admin request; direct = super-admin deletion
            $table->string('source', 20)->default('request');

            // Prevent multiple pending requests for the same employee.
            // Set to NULL when the request is reviewed.
            $table->unsignedBigInteger('pending_employee_id')
                ->nullable()
                ->unique();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('review_remarks')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->foreignId('restored_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('restored_at')->nullable();

            $table->timestamps();

            $table->index(['employee_id', 'status']);
            $table->index(['requested_by', 'status']);
        });
    }

    public function down(): void
    {
        // Avoid accidentally reactivating trashed accounts on rollback.
        if (DB::table('users')->whereNotNull('deleted_at')->exists()) {
            throw new RuntimeException(
                'Restore trashed employees before rolling back this migration.'
            );
        }

        Schema::dropIfExists('employee_deletion_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};