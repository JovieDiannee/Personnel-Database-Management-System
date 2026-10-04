<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Application Seeders
        |--------------------------------------------------------------------------
        |
        | Seeds:
        | - Office Database
        | - Super Admin Account
        |
        */

        $this->call([

            /*
            |--------------------------------------------------------------------------
            | Office Database
            |--------------------------------------------------------------------------
            |
            | Seeds:
            | - OSDS
            | - CID
            | - SGOD
            | - Office Units
            | - Sub-units / Sections
            |
            */

            OfficeDatabaseSeeder::class,


            /*
            |--------------------------------------------------------------------------
            | Super Admin
            |--------------------------------------------------------------------------
            |
            | Creates or updates the default Super Admin account.
            |
            */

            SuperAdminSeeder::class,
        ]);
    }
}