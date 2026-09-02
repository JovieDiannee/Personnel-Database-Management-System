<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetImportedData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pdms:reset-imported-data';

    /**
     * The console command description.
     */
    protected $description = 'Remove imported PDMS data while preserving Super Admin accounts.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /*
        |--------------------------------------------------------------------------
        | Confirmation
        |--------------------------------------------------------------------------
        */

        $this->warn('WARNING: This will delete imported PDMS records.');

        $this->line('');
        $this->line('The following data will be removed:');
        $this->line('- Enrollment records');
        $this->line('- Medical allowance records');
        $this->line('- Employment status records');
        $this->line('- Issued IDs');
        $this->line('- Basic information');
        $this->line('- Non-Super Admin users');
        $this->line('- Plantilla database');
        $this->line('- School database');

        $this->line('');
        $this->info('Super Admin accounts will be preserved.');

        if (! $this->confirm(
            'Are you sure you want to continue?',
            false
        )) {
            $this->info('Reset cancelled.');

            return self::SUCCESS;
        }


        /*
        |--------------------------------------------------------------------------
        | Second Confirmation
        |--------------------------------------------------------------------------
        */

        $confirmation = $this->ask(
            'Type RESET-PDMS-DATA to confirm'
        );

        if ($confirmation !== 'RESET-PDMS-DATA') {
            $this->error('Confirmation text did not match. Reset cancelled.');

            return self::FAILURE;
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Data
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Child / Dependent Tables First
            |--------------------------------------------------------------------------
            */

            DB::table('enrollments')->delete();

            DB::table('school_years')->delete();

            DB::table('medical_allowance')->delete();

            DB::table('employment_status')->delete();

            DB::table('issued_id')->delete();

            DB::table('basic_information')->delete();


            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            |
            | Preserve Super Admin accounts.
            |
            */

            DB::table('users')
                ->where('role', '!=', 'super_admin')
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | Reference Data Imported Through Excel
            |--------------------------------------------------------------------------
            */

            DB::table('plantilla_db')->delete();

            DB::table('school_db')->delete();

        });


        /*
        |--------------------------------------------------------------------------
        | Complete
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('Imported PDMS data was removed successfully.');
        $this->info('Super Admin accounts were preserved.');

        return self::SUCCESS;
    }
}