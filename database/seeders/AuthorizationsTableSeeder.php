<?php

namespace Database\Seeders;

use App\Models\Authorization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorizations = [
            ['user_id' => 'U001', 'role_group_id' => 'RG001'],
            ['user_id' => 'U002', 'role_group_id' => 'RG002'],
        ];

        foreach ($authorizations as $authorization) {
            Authorization::create($authorization);
        }
    }
}
