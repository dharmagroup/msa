<?php

namespace Database\Seeders;

use App\Models\Worklocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorklocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $worklocations = [
            ['worklocation_code' => 'WL001', 'worklocation_name' => 'Location A', 'company_code' => 'COMP001'],
            ['worklocation_code' => 'WL002', 'worklocation_name' => 'Location B', 'company_code' => 'COMP002'],
        ];

        foreach ($worklocations as $worklocation) {
            Worklocation::create($worklocation);
        }
    }
}
