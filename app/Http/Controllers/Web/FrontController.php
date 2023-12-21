<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BoardingFacilities;
use App\Models\BoardingHouses;
use App\Models\BoardingImages;
use App\Models\Districts;
use App\Models\Facilities;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return redirect()->route('front.greeting', [], 301);
    }

    // Menampilkan halaman greeting
    public function greeting()
    {
        // Mendapatkan data kos terbaik berdasarkan rating tertinggi
        $top_boarding = BoardingHouses::with('district:id,name')->orderBy('rating', 'desc')->limit(3)->get();

        // data yang akan dikirim ke view
        $data = [
            'top_boarding' => $top_boarding,
        ];

        return view('front.greeting', $data);
    }
    
    // Menampilkan halaman utama
    public function home()
    {
        // Mendapatkan fasilitas berdasarkan kategori
        $room_facilities = Facilities::where('category', 1)->get();
        $bathroom_facilities = Facilities::where('category', 2)->get();
        $general_facilities = Facilities::where('category', 3)->get();

        // Mendapatkan kecamatan
        $districts = Districts::all();

        $data = [
            'room_facilities' => $room_facilities,
            'bathroom_facilities' => $bathroom_facilities,
            'general_facilities' => $general_facilities,
            'districts' => $districts,
        ];

        return view('front.home', $data);
    }

    // Menampilkan halaman detail kos
    public function detail($id)
    {
        // Mendapatkan data gambar kos
        $kos = BoardingHouses::with('user','images')->find($id);
        $kos_images = BoardingImages::where('boarding_id', $id)->where('image_path', '!=', null)->get();
        
        // Mendapatkan fasilitas berdasarkan kategori
        $room_facilities = BoardingFacilities::where('boarding_id', $id)
            ->whereHas('facility', function ($query) {
                return $query->where('category', 1);
            })->get();

        $bathroom_facilities = BoardingFacilities::where('boarding_id', $id)
            ->whereHas('facility', function ($query) {
                return $query->where('category', 2);
            })->get();

        $general_facilities = BoardingFacilities::where('boarding_id', $id)
            ->whereHas('facility', function ($query) {
                return $query->where('category', 3);
            })->get();
        
        $data = [
            'kos' => $kos,
            'kos_images' => $kos_images,
            'room_facilities' => $room_facilities,
            'bathroom_facilities' => $bathroom_facilities,
            'general_facilities' => $general_facilities,
        ];

        return view('front.detail', $data);
    }

    // Menampilkan halaman pencarian rute
    public function routeDirections(Request $request)
    {
        $boarding_id = $request->boarding;
        $boarding_data = BoardingHouses::find(base64_decode($boarding_id));

        if (!$boarding_data) {
            return redirect()->route('front.home')->with('error', 'Data kos tidak ditemukan');
        }

        $data = [
            'boarding_data' => $boarding_data,
        ];

        return view('front.route-directions', $data);
    }
}
