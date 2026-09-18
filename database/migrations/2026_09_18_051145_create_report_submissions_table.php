<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')
                ->constrained('reports')
                ->restrictOnDelete();

            $table->string('school_id', 10)->index();

            // Empty until a user submits the report.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', [
                'Verified',
                'Done',
                'Pending',
            ])->default('Pending');

            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('validated_at')->nullable();

            $table->timestamps();

            // One submission record per school for each report.
            $table->unique(
                ['report_id', 'school_id'],
                'report_submissions_report_school_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_submissions');
    }
};