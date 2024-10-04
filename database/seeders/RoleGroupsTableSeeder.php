<?php

namespace Database\Seeders;

use App\Models\RoleGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleGroups = [
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "0ba4378e-20bc-43a9-a43a-5dbe9926d723"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "0dee6b54-bf2c-4448-9853-170a6cc6c524"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "0fe06e3d-36ac-48ca-a28a-07ed8505224a"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "236c3d0b-f228-4241-90b0-202eca2a3ce7"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "2f18fb28-0d0a-48bd-a209-08566e05d420"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "345f64cf-a39a-4174-9b0b-374a2d97a528"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "376eae48-77df-4078-a88e-02d725cce91d"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "46dd5ffb-8011-4049-b7b4-8fee0daef869"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "67a63e75-a630-4518-8af3-66cca58282a8"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "702cb5ee-77f0-4f3c-8994-bc9278697786"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "797924e2-1d24-42ce-b8de-7b0002a677a7"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "838b5cda-0d7b-491b-ab6c-d5c6fa806477"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "be2fd96e-dcc7-450b-b4b9-7e9c42f44d35"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "e8df8f03-1f7f-4af9-8193-d65b4474118d"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "ecb21237-615c-4f26-b578-588e6a6815b6"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "f0412351-c930-4de4-a8e5-09b873e43b69"],
            ['role_id' => '6fc9b13e-3415-4f7f-bea3-55f30eed942f', 'module_id' => "f413e066-1fc3-4040-a6f8-de79f0ce0707"],
        ];

        foreach ($roleGroups as $roleGroup) {
            RoleGroup::create($roleGroup);
        }
    }
}
