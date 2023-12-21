<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouses;
use App\Models\BoardingImages;
use App\Utils\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class KosManager extends Controller
{
    // Mendapatkan data kos
    public function getData(Request $request)
    {
        $data = BoardingHouses::with('district', 'document');

        // Filter data
        $role = Auth::user()->roles->pluck('name')[0];
        if ($role != Constant::ROLE_SUPERADMIN) {
            $data = $data->where('user_id', Auth::user()->id);
        }

        // Konfigurasi datatables
        $data = $data->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $user_role = Auth::user()->roles->pluck('name')[0];

                $btn_superadmin_1 = '';
                $btn_superadmin_2 = '';

                if ($user_role == Constant::ROLE_SUPERADMIN) {
                    $color = '';
                    $icon = '';

                    if ($row->is_active) {
                        $color = 'btn-outline-secondary';
                        $icon = '<i class="fas fa-ban"></i>';
                    } else {
                        $color = 'btn-outline-primary';
                        $icon = '<i class="fas fa-check"></i>';
                    }

                    $btn_superadmin_1 = '<a href="javascript:void(0)" data-id="'. $row->id .'" data-status="'. $row->is_active .'" class="change-status btn btn-sm '. $color .' btn-block">' . $icon . '</a>';
                    $btn_superadmin_2 = '<a href="javascript:void(0)" data-id="'. $row->id .'" class="document-preview btn btn-sm btn-outline-dark btn-block"><i class="fas fa-file"></i></a>';
                }

                $btn_admin_1 = '<a href="javascript:void(0)" data-id="'. $row->id .'" class="edit btn btn-sm btn-outline-success btn-block"><i class="fas fa-edit"></i></a>';
                $btn_admin_2 = ' <a href="javascript:void(0)" data-id="'. $row->id .'" class="delete btn btn-sm btn-outline-danger btn-block"><i class="fas fa-trash"></i></a>';
                $btn_admin_3 = '';

                $document_status = $row->document ? $row->document->status : 2;
                if ($user_role == Constant::ROLE_ADMIN) {
                    if ($document_status == 1) {
                        $btn_admin_3 = '<a href="javascript:void(0)" data-id="'. $row->id .'" class="pending-document btn btn-sm btn-outline-info btn-block"><i class="fas fa-info-circle"></i></a>';
                    }
                    elseif ($document_status == 3) {
                        $btn_admin_3 = '<a href="javascript:void(0)" data-id="'. $row->id .'" class="update-document btn btn-sm btn-outline-primary btn-block"><i class="fas fa-file"></i></a>';
                    }
                }
                
                return '<div class="row">
                            <div class="col-lg-6 col-12 p-1">
                                '. $btn_superadmin_1 .'
                            </div>
                            <div class="col-lg-6 col-12 p-1">
                                '. $btn_superadmin_2 .'
                            </div>
                            <div class="col-lg-6 col-12 p-1">
                                '. $btn_admin_1 .'
                            </div>
                            <div class="col-lg-6 col-12 p-1">
                                '. $btn_admin_2 .'
                            </div>
                            <div class="col-12 p-1">
                                '. $btn_admin_3 .'
                            </div>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Mendapatkan data gambar cover
    public function getCoverImages(Request $request)
    {
        // Get data
        $images = BoardingImages::where('boarding_id', $request->id)
            ->where('sequence', '<=', 10)
            ->get();

        return DataTables::of($images)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn = '<a href="javascript:void(0)" data-sequence="'. $row->sequence .'" class="edit-cover btn btn-sm btn-outline-dark btn-sm"><i class="fas fa-edit"></i></a>';

                if ($row->image_path != null) {
                    $btn .= ' <a href="javascript:void(0)" data-id="'. $row->id .'" class="delete-cover btn btn-sm btn-outline-danger btn-sm"><i class="fas fa-trash"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
