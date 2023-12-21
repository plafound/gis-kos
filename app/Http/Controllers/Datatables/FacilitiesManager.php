<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Facilities;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FacilitiesManager extends Controller
{
    // Mendapatkan data fasilitas
    public function getAll(Request $request)
    {
        // Get data
        $data = Facilities::all();

        // Konfigurasi datatables
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn = '<a href="javascript:void(0)" data-id="'. $row->id .'" class="edit btn btn-sm btn-outline-success btn-sm"><i class="fas fa-pencil-alt"></i></a>';
                $btn = $btn.' <a href="javascript:void(0)" data-id="'. $row->id .'" class="delete btn btn-sm btn-outline-danger btn-sm"><i class="fas fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
