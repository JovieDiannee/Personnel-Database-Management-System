<?php

namespace Database\Seeders;

use App\Models\OfficeGroup;
use App\Models\OfficeUnit;
use Illuminate\Database\Seeder;

class OfficeDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | OSDS
        |--------------------------------------------------------------------------
        */

        $osds = OfficeGroup::create([
            'code' => 'OSDS',
            'name' => 'Office of the Schools Division Superintendent',
            'sort_order' => 1,
        ]);

        OfficeUnit::create([
            'office_group_id' => $osds->id,
            'code' => 'SDS',
            'name' => 'Schools Division Superintendent',
            'unit_type' => 'Office',
            'sort_order' => 1,
        ]);

        OfficeUnit::create([
            'office_group_id' => $osds->id,
            'code' => 'ASDS',
            'name' => 'Assistant Schools Division Superintendent',
            'unit_type' => 'Office',
            'sort_order' => 2,
        ]);

        OfficeUnit::create([
            'office_group_id' => $osds->id,
            'code' => 'ICT',
            'name' => 'ICT Unit',
            'unit_type' => 'Unit',
            'sort_order' => 3,
        ]);

        OfficeUnit::create([
            'office_group_id' => $osds->id,
            'code' => 'LEGAL',
            'name' => 'Legal Unit',
            'unit_type' => 'Unit',
            'sort_order' => 4,
        ]);

        OfficeUnit::create([
            'office_group_id' => $osds->id,
            'code' => 'ACCOUNTING',
            'name' => 'Accounting Unit',
            'unit_type' => 'Unit',
            'sort_order' => 5,
        ]);

        OfficeUnit::create([
            'office_group_id' => $osds->id,
            'code' => 'FINANCE',
            'name' => 'Finance Unit',
            'unit_type' => 'Unit',
            'sort_order' => 6,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin = OfficeUnit::create([
            'office_group_id' => $osds->id,
            'code' => 'ADMIN',
            'name' => 'Administrative Division',
            'unit_type' => 'Division',
            'sort_order' => 7,
        ]);

        foreach ([
            ['RECORDS', 'Records', 1],
            ['CASH', 'Cash', 2],
            ['SUPPLY', 'Supply', 3],
            ['PROCUREMENT', 'Procurement', 4],
        ] as [$code, $name, $order]) {

            OfficeUnit::create([
                'office_group_id' => $osds->id,
                'parent_id' => $admin->id,
                'code' => $code,
                'name' => $name,
                'unit_type' => 'Unit',
                'sort_order' => $order,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Personnel Unit
        |--------------------------------------------------------------------------
        */

        $personnel = OfficeUnit::create([
            'office_group_id' => $osds->id,
            'parent_id' => $admin->id,
            'code' => 'PERSONNEL',
            'name' => 'Personnel Unit',
            'unit_type' => 'Unit',
            'sort_order' => 5,
        ]);

        foreach ([
            ['PERSONNEL-PROPER', 'Personnel Proper', 1],
            ['PERSONNEL-FILES', 'Personnel Files', 2],
            ['PAYROLL', 'Payroll', 3],
            ['REMITTANCE', 'Remittance', 4],
        ] as [$code, $name, $order]) {

            OfficeUnit::create([
                'office_group_id' => $osds->id,
                'parent_id' => $personnel->id,
                'code' => $code,
                'name' => $name,
                'unit_type' => 'Section',
                'sort_order' => $order,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CID
        |--------------------------------------------------------------------------
        */

        $cid = OfficeGroup::create([
            'code' => 'CID',
            'name' => 'Curriculum Implementation Division',
            'sort_order' => 2,
        ]);

        foreach ([
            ['ALS', 'Alternative Learning System', 1],
            ['LRM', 'Learning Resource Management', 2],
            ['IS', 'Instructional Supervision', 3],
            ['DIS', 'District Instructional Supervision', 4],
        ] as [$code, $name, $order]) {

            OfficeUnit::create([
                'office_group_id' => $cid->id,
                'code' => $code,
                'name' => $name,
                'unit_type' => 'Unit',
                'sort_order' => $order,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SGOD
        |--------------------------------------------------------------------------
        */

        $sgod = OfficeGroup::create([
            'code' => 'SGOD',
            'name' => 'School Governance and Operations Division',
            'sort_order' => 3,
        ]);

        foreach ([
            ['HNU', 'HNU', 1],
            ['DRRM', 'DRRM', 2],
            ['YFD', 'YFD', 3],
            ['SPORTS', 'SPORTS', 4],
            ['ME', 'M&E', 5],
            ['PLANNING', 'Planning Unit', 6],
            ['HRD', 'HRD', 7],
            ['ENGINEERING', 'Engineering', 8],
            ['SMN', 'SMN', 9],
        ] as [$code, $name, $order]) {

            OfficeUnit::create([
                'office_group_id' => $sgod->id,
                'code' => $code,
                'name' => $name,
                'unit_type' => 'Unit',
                'sort_order' => $order,
            ]);
        }
    }
}