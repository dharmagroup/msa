<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $companies = [
            ['company_code' => 'COMP001', 'company_name' => 'Company A'],
            ['company_code' => 'COMP002', 'company_name' => 'Company B'],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
