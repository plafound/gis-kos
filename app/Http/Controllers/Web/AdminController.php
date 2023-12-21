<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Districts;
use App\Models\Facilities;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Menampilkan halaman dashboard
    public function dashboard()
    {
        if (auth()->user()->hasRole('superadmin')) {
            return view('admin.su_dashboard');
        } else if (auth()->user()->hasRole('admin')) {
            return view('admin.admin_dashboard');
        }
    }

    // Menampilkan halaman kelola kos
    public function kosManagerIndex()
    {
        $districts = Districts::all();

        $data = [
            'districts' => $districts,
        ];

        return view('admin.kos_manager.list', $data);
    }

    // Menampilkan halaman edit data kos
    public function kosManagerEdit($id)
    {
        // Mendapatkan kecamatan
        $districts = Districts::all();

        // Mendapatkan fasilitas berdasarkan kategori
        $bedroom = Facilities::where('category', 1)->get();
        $bathroom = Facilities::where('category', 2)->get();
        $general = Facilities::where('category', 3)->get();

        $data = [
            'districts' => $districts,
            'bedroom' => $bedroom,
            'bathroom' => $bathroom,
            'general' => $general,
        ];

        return view('admin.kos_manager.edit', $data);
    }

    // Menampilkan halaman kelola fasilitas
    public function facilitiesManagerIndex()
    {
        return view('admin.facilities_manager.list');
    }
}
