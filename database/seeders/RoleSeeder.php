<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    
    public function run()
    {
        $roles = [
            [
                'name' => 'Admin',
                'access_permission' => 'Dashboard,User Management,Survey Management,Report Management',
            ],
            [
                'name' => 'User',
                'access_permission' => 'Account Management,View Survey,Submit Responses,Review Result',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }

}
