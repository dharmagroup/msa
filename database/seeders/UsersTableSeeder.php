<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan untuk mengimpor model User
use Illuminate\Support\Str;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
               
                'username' => 'john_doe',
                'password' => bcrypt('password123'), // Enkripsi password
                'employee_id' => 'E001',
                'user_type' => 'employee',
            ],
            [
                
                'username' => 'admin_user',
                'password' => bcrypt('adminpass'),
                'employee_id' => 'E002',
                'user_type' => 'admin',
            ],
            [
                
                'username' => 'super_admin',
                'password' => bcrypt('superadminpass'),
                'employee_id' => 'E003',
                'user_type' => 'superadmin',
            ],
            [
                
                'username' => 'dev_user',
                'password' => bcrypt('devpass'),
                'employee_id' => 'E004',
                'user_type' => 'developer',
            ],
        ];

        // Insert data menggunakan model User
        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
