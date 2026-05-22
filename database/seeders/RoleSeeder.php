<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'id'=>1,
                'role_name'=>'Admin'
            ],
            [
                'id'=>2,
                'role_name'=>'Manager'
            ],
            [
                'id'=>3,
                'role_name'=>'Employee'
            ]
        ]);
    }
}
