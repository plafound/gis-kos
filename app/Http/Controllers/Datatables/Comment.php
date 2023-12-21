<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class Comment extends Controller
{
    // Mendapatkan data ulasan
    public function getData(Request $request, $boarding_id)
    {
        // Get data
        $data = Comments::where('boarding_id', $boarding_id)->orderBy('created_at', 'desc');

        // Konfigurasi datatables
        $datatables = DataTables::of($data)
            ->addIndexColumn()
            ->make(true);

        return $datatables;
    }
}
