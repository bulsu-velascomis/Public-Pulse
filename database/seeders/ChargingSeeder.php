<?php

namespace Database\Seeders;

use App\Models\Charging;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChargingSeeder extends Seeder
{
    public function run()
    {
        $chargings = [
            ["name" => "Management Information System", "code" => "MIS"],
            ["name" => "Human Resources", "code" => "HR"],
            ["name" => "Administration", "code" => "Admin"],
        ];

        foreach ($chargings as $charging) {
            Charging::create($charging);
        }
    }
}
