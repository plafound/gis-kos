<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Constant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserManager extends Controller
{
    // Mendapatkan data user
    public function getAll(Request $request) {
        $data = User::query();

        if ($request->has('role')) {
            $role = $request->role;

            if ($role == Constant::ROLE_SUPERADMIN) {
                $data = $data->role(Constant::ROLE_SUPERADMIN);
            } else if ($role == Constant::ROLE_ADMIN) {
                $data = $data->role(Constant::ROLE_ADMIN);
            } else if ($role == Constant::ROLE_USER) {
                $data = $data->role(Constant::ROLE_USER);
            }
        }

        $data = $data->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn = '<a href="javascript:void(0)" data-id="'. $row->id .'" class="editAdmin btn btn-sm btn-outline-success"><i class="fas fa-pencil-alt"></i></a>';
                $btn = $btn . ' <a href="javascript:void(0)" data-id="'. $row->id .'" class="deleteAdmin btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
