<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [ 'module_name' => 'Suggestion System', 'module_parent' => '46dd5ffb-8011-4049-b7b4-8fee0daef869', 'url' => null,'icon' => 'bi bi-lightbulb'],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
