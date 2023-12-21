<?php

namespace App\Services;

class HaversineService
{
    /**
     * Calculate the distance between two points using the Haversine formula.
     * @param array $latlong_1
     * @param array $latlong_2
     * @return float
     */
    public function calculate(array $latlong_1, array $latlong_2)
    {
        $earthRadius = 6371; // in kilometers

        // convert to radians
        $lat1 = deg2rad($latlong_1[0]);
        $lon1 = deg2rad($latlong_1[1]);
        $lat2 = deg2rad($latlong_2[0]);
        $lon2 = deg2rad($latlong_2[1]);

        // calculate the differences
        $latDiff = $lat2 - $lat1;
        $lonDiff = $lon2 - $lon1;

        // apply the Haversine formula
        $a = sin($latDiff/2) * sin($latDiff/2) +
            cos($lat1) * cos($lat2) *
            sin($lonDiff/2) * sin($lonDiff/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        $distance = $earthRadius * $c;

        // return the distance in kilometers
        return $distance;
    }
}