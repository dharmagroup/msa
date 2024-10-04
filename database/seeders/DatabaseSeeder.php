<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // CompanyTableSeeder::class,
            // WorklocationsTableSeeder::class,
            // EmployeesTableSeeder::class,
            // RolesTableSeeder::class,
            RoleGroupsTableSeeder::class,
            // ModulesTableSeeder::class,
            // AuthorizationsTableSeeder::class,
            // UsersTableSeeder::class,
        ]);
    }
}
