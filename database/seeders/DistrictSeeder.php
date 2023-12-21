<?php

namespace Database\Seeders;

use App\Models\BoardingHouses;
use App\Models\Districts;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fill districts table
        Districts::firstOrCreate(['name' => 'Blimbing']);
        Districts::firstOrCreate(['name' => 'Kedungkandang']);
        Districts::firstOrCreate(['name' => 'Klojen']);
        Districts::firstOrCreate(['name' => 'Lowokwaru']);
        Districts::firstOrCreate(['name' => 'Sukun']);


        // Fill district_id in boarding_houses table with random id from districts table
        $boardingHouses = BoardingHouses::all();

        // Loop through boarding_houses table
        foreach ($boardingHouses as $boardingHouse) {
            // If district_id is null, fill it
            if ($boardingHouse->district_id == null) {
                $boardingHouse->district_id = Districts::all()->random()->id;
                $boardingHouse->save();
            }
        }
    }
}
