<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouses;
use App\Models\Districts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    // Mendapatkan data ringkasan
    public function getAdminSummaryData()
    {
        // Total admin
        $admin_total = DB::table('model_has_roles')
            ->where('role_id', 2)
            ->count();

        // Total kos
        $boarding_total = BoardingHouses::query();
        if (auth()->user()->hasRole('superadmin')) {
            $boarding_total = $boarding_total->count();
        } else if (auth()->user()->hasRole('admin')) {
            $boarding_total = $boarding_total->where('user_id', auth()->user()->id)->count();
        }

        // Total kamar
        $room_total = BoardingHouses::query();
        if (auth()->user()->hasRole('superadmin')) {
            $room_total = $room_total->sum('capacity');
        } else if (auth()->user()->hasRole('admin')) {
            $room_total = $room_total->where('user_id', auth()->user()->id)->sum('capacity');
        }

        // Total kamar terisi
        $room_filled = BoardingHouses::query();
        if (auth()->user()->hasRole('superadmin')) {
            $room_filled = $room_filled->sum('filled_capacity');
        } else if (auth()->user()->hasRole('admin')) {
            $room_filled = $room_filled->where('user_id', auth()->user()->id)->sum('filled_capacity');
        }

        // Return response
        return response()->json([
            'error' => false,
            'message' => 'Berhasil mendapatkan data ringkasan',
            'data' => [
                'admin_total' => $admin_total,
                'boarding_total' => $boarding_total,
                'room_total' => $room_total,
                'room_filled' => $room_filled
            ]
        ]);
    }

    // Mendapatkan total kos per kecamatan
    public function getBoardingHouseTotal()
    {
        // Get data
        $data = Districts::select('districts.id', 'districts.name', DB::raw('COUNT(boarding_houses.district_id) as total'))
            ->leftJoin('boarding_houses', 'districts.id', '=', 'boarding_houses.district_id')
            ->groupBy('districts.id', 'districts.name')
            ->get();

        // Return response
        return response()->json([
            'error' => false,
            'message' => 'Berhasil mendapatkan data total kos',
            'data' => $data
        ]);
    }

    // Mendapatkan data kamar terisi
    public function getRoomFilled()
    {
        // Get data
        $data = BoardingHouses::select('name', 'capacity', 'filled_capacity', DB::raw("CONCAT(ROUND(filled_capacity / capacity * 100, 0), '%') AS filled_capacity_percentage"))
            ->orderBy('filled_capacity', 'DESC')
            ->limit(5)
            ->get();

        // Return response
        return response()->json([
            'error' => false,
            'message' => 'Berhasil mendapatkan data kamar terisi',
            'data' => $data
        ]);
    }
}
