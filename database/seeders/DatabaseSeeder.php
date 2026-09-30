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

        $this->call([
            OfficeDatabaseSeeder::class,
        ]);
    }
}