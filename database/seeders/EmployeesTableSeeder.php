<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            ['employee_number' => 'EMP001', 'employee_name' => 'John Doe', 'company_code' => 'COMP001', 'worklocation_code' => 'WL001', 'so_code' => 'SO001', 'employee_start_date' => now(), 'status' => 'active'],
            ['employee_number' => 'EMP002', 'employee_name' => 'Jane Smith', 'company_code' => 'COMP002', 'worklocation_code' => 'WL002', 'so_code' => 'SO002', 'employee_start_date' => now(), 'status' => 'active'],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
