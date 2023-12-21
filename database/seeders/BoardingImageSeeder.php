<?php

namespace Database\Seeders;

use App\Models\BoardingHouses;
use App\Models\BoardingImages;
use Illuminate\Database\Seeder;

class BoardingImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all boarding houses
        $boardingHouses = BoardingHouses::all();

        // Loop through each boarding house
        foreach ($boardingHouses as $boardingHouse) {
            // Create 3 boarding images for each boarding house
            for ($i = 1; $i <= 10; $i++) {
                // Check if the boarding house image already exists
                $check = BoardingImages::where('boarding_id', $boardingHouse->id)
                    ->where('sequence', $i)
                    ->first();
                
                // If the boarding house image does not exist, create it
                if (!$check) {
                    // Create the boarding house image
                    BoardingImages::create([
                        'boarding_id' => $boardingHouse->id,
                        'sequence' => $i,
                    ]);
                }
            }
        }
    }
}
