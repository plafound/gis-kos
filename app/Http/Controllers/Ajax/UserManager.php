<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserManager\StoreRequest;
use App\Http\Requests\Admin\UserManager\UpdateRequest;
use App\Models\BoardingDocuments;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManager extends Controller
{
    // Mendapatkan data user
    public function get($id)
    {
        try {
            // Get data user
            $user = User::find($id);
    
            // Throw error jika user tidak ditemukan
            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }
    
            return response()->json([
                'error' => false,
                'message' => 'Berhasil mendapatkan data admin',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ]);
        }
    }

    // Mendapatkan data user
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $is_created_by_superadmin = Auth::user()->hasRole('superadmin') ? true : false;
            $user_status = $is_created_by_superadmin ? 3 : 1; // 1 = pending, 3 = approved

            // Membuat data user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $user_status
            ]);

            // Menetapkan role user menjadi admin
            $user->assignRole('admin');

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Admin baru berhasil dibuat',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Gagal membuat admin baru',
                'data' => $th
            ]);
        }
    }

    // Memperbarui data user
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Get data user
            $user = User::find($id);

            // Throw error jika user tidak ditemukan
            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            // Memperbarui data user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Admin berhasil diupdate',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengupdate admin',
                'data' => $th
            ]);
        }
    }

    // Menghapus data user
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Get data user
            $user = User::find($id);

            // Throw error jika user tidak ditemukan
            if (!$user) {
                throw new \Exception('User tidak ditemukan');
            }

            // Menghapus data user
            $user->delete();

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Admin berhasil dihapus',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Gagal menghapus admin',
                'data' => $th
            ]);
        }
    }

    public function uploadDocument(Request $request, $id)
    {
        try {
            $service = new UserService();
            $upload = $service->uploadDocument($id, $request->file('document_file'));

            if ($upload['error']) {
                throw new \Exception($upload['message']);
            }

            return response()->json([
                'error' => false,
                'message' => 'Berhasil mengupload ulang dokumen',
                'data' => null
            ]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . " | on line: " . $th->getLine() . " | " . $th->getMessage());
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }
}
