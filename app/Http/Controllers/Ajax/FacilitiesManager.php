<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Facilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilitiesManager extends Controller
{
    // Mendapatkan data fasilitas
    public function get($id = null)
    {
        try {
            // Get data fasilitas
            $facilities = Facilities::query();
            if ($id) {
                $facilities = Facilities::find($id);
            } else {
                $facilities = Facilities::all();
            }

            // Return response
            return response()->json([
                'error' => false,
                'message' => 'Berhasil mendapatkan data fasilitas',
                'data' => $facilities
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ]);
        }
    }

    public function store(Request $request)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {
            // Menambahkan data fasilitas
            $facilities = Facilities::create([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            // Commit transaksi
            DB::commit();

            // Return response
            return response()->json([
                'error' => false,
                'message' => 'Berhasil menambahkan data fasilitas',
                'data' => $facilities
            ]);
        } catch (\Throwable $th) {
            // Rollback transaksi
            DB::rollback();

            // Return response
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ]);
        }
    }

    // Mengubah data fasilitas
    public function update(Request $request, $id)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {
            // Cek apakah fasilitas ditemukan
            $facilities = Facilities::find($id);

            // Throw error jika fasilitas tidak ditemukan
            if (!$facilities) {
                throw new \Exception('Fasilitas tidak ditemukan');
            }

            // Update data fasilitas
            $facilities->update([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            // Commit transaksi
            DB::commit();

            // Return response
            return response()->json([
                'error' => false,
                'message' => 'Berhasil mengubah data fasilitas',
                'data' => $facilities
            ]);
        } catch (\Throwable $th) {
            // Rollback transaksi
            DB::rollback();

            // Return response
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ]);
        }
    }

    // Menghapus data fasilitas
    public function destroy($id)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {
            // Cek apakah fasilitas ditemukan
            $facilities = Facilities::find($id);

            // Throw error jika fasilitas tidak ditemukan
            if (!$facilities) {
                throw new \Exception('Fasilitas tidak ditemukan');
            }

            // Hapus data fasilitas
            $facilities->delete();

            // Commit transaksi
            DB::commit();

            // Return response
            return response()->json([
                'error' => false,
                'message' => 'Berhasil menghapus data fasilitas',
                'data' => $facilities
            ]);
        } catch (\Throwable $th) {
            // Rollback transaksi
            DB::rollback();

            // Return response
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ]);
        }
    }
}
