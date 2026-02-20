<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssignRoleSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where("name", "Admin")->first();

        User::create([
            "firstname" => "Admin",
            "lastname" => "Admin",
            "username" => "admin",
            "password" => bcrypt("admin123"),
            "role_id" => $adminRole->id,
            "charging_id" => 001,
        ]);
    }
}
