<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KosManager\ImageUploadRequest;
use App\Http\Requests\Admin\KosManager\StoreRequest;
use App\Http\Requests\Admin\KosManager\UpdateRequest;
use App\Models\BoardingDocuments;
use App\Models\BoardingFacilities;
use App\Models\BoardingHouses;
use App\Models\BoardingImages;
use App\Services\BoardingService;
use App\Services\HaversineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KosManager extends Controller
{
    // Mendapatkan data kos
    public function getAll(Request $request)
    {
        // Init filters
        $filters = [];

        // Cek apakah request get_all bernilai false
        if ($request->has('get_all') && $request->get_all == 'false') {
            $filter_facility = $request->facilities ?? [];
            $filter_price = [
                'min' => $request->min_price ?? 0,
                'max' => $request->max_price ?? 0,
            ];
    
            $filters = [
                'facilities' => $filter_facility,
                'type' => $request->type ?? "all",
                'district' => $request->district ?? "all",
                'price' => $filter_price,
            ];
        }

        try {
            // Get boarding houses
            $boarding_service = new BoardingService();
            $kos = $boarding_service->get($filters);

            // Check if there is an error
            if ($kos['error']) {
                throw new \Exception($kos['message']);
            }

            // Calculate distance using haversine formula
            $haversine_data = null;
            $haversine_service = new HaversineService();

            foreach ($kos['data'] as $key => $value) {
                $haversine_data[$key]['id'] = $value->id;
                $haversine_data[$key]['name'] = $value->name;
                $haversine_data[$key]['address'] = $value->address;
                $haversine_data[$key]['latitude'] = $value->latitude;
                $haversine_data[$key]['longitude'] = $value->longitude;
                $haversine_data[$key]['distance'] = $haversine_service->calculate(
                    [
                        $request->current_location[0],
                        $request->current_location[1],
                    ],
                    [
                        $value->latitude,
                        $value->longitude,
                    ]
                );
            }

            // Jika data haversine tidak kosong, maka urutkan data berdasarkan jarak
            if (!empty($haversine_data)) {
                // Sort the data by distance
                usort($haversine_data, function($a, $b) {
                    return $a['distance'] <=> $b['distance'];
                });
            }

            return response()->json([
                'error' => false,
                'message' => 'Berhasil mendapatkan data kos',
                'data' => [
                    'boarding' => $kos['data'],
                    'haversine' => $haversine_data,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ]);
        }
    }

    // Mendapatkan data kos
    public function get($id)
    {
        try {
            // Get boarding houses
            $kos = BoardingHouses::with('user')->find($id);

            // Throw exception jika kos tidak ditemukan
            if (!$kos) {
                throw new \Exception('Kos tidak ditemukan');
            }

            return response()->json([
                'error' => false,
                'message' => 'Berhasil mendapatkan data kos',
                'data' => $kos
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ]);
        }
    }

    // Mendapatkan data fasilitas kos
    public function getFacilities($id)
    {
        try {
            // Get boarding houses
            $facilities = BoardingFacilities::with('facility')->where('boarding_id', $id)->get();

            return response()->json([
                'error' => false,
                'message' => 'Berhasil mendapatkan data fasilitas kos',
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

    // Menyimpan data kos
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            // Menyimpan data kos
            $kos = BoardingHouses::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'address' => $request->address,
                'district_id' => $request->district_id,
                'postal_code' => $request->postal_code,
                'latitude' => str_replace(',', '.', $request->latitude),
                'longitude' => str_replace(',', '.', $request->longitude),
                'phone_number' => $request->phone_number,
                'type' => $request->type,
                'capacity' => str_replace('.', '', $request->capacity),
                'filled_capacity' => 0,
                'price' => str_replace('.', '', $request->price),
                'is_active' => false,
            ]);

            // Init gambar kos
            for ($i = 1; $i <= 10; $i++) {
                // Check if the boarding house image already exists
                $check = BoardingImages::where('boarding_id', $kos->id)
                    ->where('sequence', $i)
                    ->first();
                
                // If the boarding house image does not exist, create it
                if (!$check) {
                    // Create the boarding house image
                    BoardingImages::create([
                        'boarding_id' => $kos->id,
                        'sequence' => $i,
                    ]);
                }
            }

            # Upload Document #
            if ($request->hasFile('document_file')) {
                // Get document file
                $document_file = $request->file('document_file');
    
                // Init user service
                $boardingService = new BoardingService();
                $upload = $boardingService->uploadDocument($kos->id, $document_file);
    
                if ($upload['error']) {
                    throw new \Exception($upload['message']);
                }
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Kos baru berhasil dibuat',
                'data' => $kos
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    // Mengupdate data kos
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Get boarding house
            $kos = BoardingHouses::find($id);

            // Throw exception jika kos tidak ditemukan
            if (!$kos) {
                throw new \Exception('Kos tidak ditemukan');
            }

            // Update data kos
            $kos->update([
                'name' => $request->name,
                'address' => $request->address,
                'district_id' => $request->district_id,
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'phone_number' => $request->phone_number,
                'type' => $request->type,
                'capacity' => str_replace('.', '', $request->capacity),
                'filled_capacity' => str_replace('.', '', $request->filled_capacity),
                'price' => str_replace('.', '', $request->price),
            ]);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Kos berhasil diupdate',
                'data' => $kos
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    // Menghapus data kos
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Get boarding house
            $kos = BoardingHouses::find($id);

            // Throw exception jika kos tidak ditemukan
            if (!$kos) {
                throw new \Exception('Kos tidak ditemukan');
            }

            // Hapus data kos
            $kos->delete();

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Kos berhasil dihapus',
                'data' => $kos
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    // Mengupdate status kos
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Get boarding house
            $kos = BoardingHouses::find($id);

            // Throw exception jika kos tidak ditemukan
            if (!$kos) {
                throw new \Exception('Kos tidak ditemukan');
            }

            // Untuk menentukan response text
            $response_text = '';
            if ($request->is_active == 0) {
                $response_text = 'dinonaktifkan';
            }else{
                $response_text = 'diaktifkan';
            }

            // Simpan data status kos
            $kos->is_active = $request->is_active;
            $kos->save();

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Kos berhasil ' . $response_text,
                'data' => $kos
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    // Mengupdate fasilitas kos
    public function updateFacilities(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get boarding house
            $kos = BoardingHouses::find($request->boarding_id);

            // Throw exception jika kos tidak ditemukan
            if (!$kos) {
                throw new \Exception('Kos tidak ditemukan');
            }

            // Jika request added tidak kosong, maka tambahkan fasilitas
            if ($request->has('added') && !empty($request->added)) {
                // Looping added
                foreach ($request->added as $key => $value) {
                    BoardingFacilities::create([
                        'boarding_id' => $request->boarding_id,
                        'facility_id' => $value
                    ]);
                }
            }

            // Jika request removed tidak kosong, maka hapus fasilitas
            if ($request->has('removed') && !empty($request->removed)) {
                // Looping removed
                foreach ($request->removed as $key => $value) {
                    BoardingFacilities::where('boarding_id', $request->boarding_id)->where('facility_id', $value)->delete();
                }
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Fasilitas kos berhasil diupdate',
                'data' => null
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    // Mendapatkan data dokumen kos
    public function getDocument($id)
    {
        try {
            // Get boarding house document
            $document = BoardingDocuments::with('boarding_house:id,name,is_active')->where('boarding_id', $id)->first();

            return response()->json([
                'error' => false,
                'message' => 'Berhasil mendapatkan dokumen',
                'data' => $document
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    // Memverifikasi dokumen kos
    public function verifyDocument(Request $request, $id)
    {
        // Inisialisasi variabel pesan
        $message = '';

        DB::beginTransaction();
        try {
            $status = $request->status;
            $boarding_docs = BoardingDocuments::with('boarding_house')->where('id', $id)->first();

            // Jika status `accept`, maka status kos menjadi aktif
            if ($status == 'accept') {
                $message = 'memverifikasi';
                $boarding_docs->status = 2;

                // Update boarding_house status
                $boarding_house = BoardingHouses::find($boarding_docs->boarding_id);
                $boarding_house->is_active = 1;
                $boarding_house->save();
            } else {
                $message = 'menolak';
                $boarding_docs->status = 3;
            }

            // Simpan data dokumen
            $boarding_docs->save();

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => "Berhasil $message dokumen kos",
                'data' => null
            ]);
        } catch (\Throwable $th) {
            Log::error($th);

            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => "Gagal $message dokumen kos",
                'data' => $th
            ], 500);
        }
    }

    // Mengupload gambar kos
    public function imageUploader(ImageUploadRequest $request)
    {
        DB::beginTransaction();
        try {
            $uploadType = $request->upload_type;
            $sequence = 0;
            $fileName = "";
            $file = $request->file('image_file');

            // Check if upload type is new or update
            if ($uploadType == 'new') {
                $sequence = BoardingImages::where('boarding_id', $request->boarding_id)->max('sequence') + 1;
            } else {
                $sequence = $request->sequence;

                // Check if image already exists
                $isExist = BoardingImages::where('boarding_id', $request->boarding_id)
                    ->where('sequence', $sequence)
                    ->first();
    
                if ($isExist) {
                    // check if old file exists and delete it
                    $oldFile = $isExist->image_path;
        
                    if (!empty($oldFile)) {
                        $oldFile = public_path($oldFile);
                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                }
            }

            // Check if file request is not empty
            if (!empty($request->file('image_file'))) {
                // Upload file
                $fileName = time() . '_' . $file->getClientOriginalName();  
                $file->move(public_path('uploads/images'), $fileName);
            }

            // Save to database
            BoardingImages::updateOrCreate(
                [
                    'boarding_id' => $request->boarding_id,
                    'sequence' => $sequence
                ],
                [
                    'image_path' => 'uploads/images/' . $fileName
                ]
            );

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Gambar berhasil diupload',
                'data' => null
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    public function deleteCoverImage($id)
    {
        DB::beginTransaction();
        try {
            $isExist = BoardingImages::find($id);

            if ($isExist) {
                // check if old file exists and delete it
                $oldFile = $isExist->image_path;

                if (!empty($oldFile)) {
                    $oldFile = public_path($oldFile);
                    if (file_exists($oldFile)) {
                        unlink($oldFile);

                        // Change image path to null
                        $isExist->image_path = null;
                        $isExist->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Gambar berhasil dihapus',
                'data' => null
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }

    // Memperbarui dokumen pelengkap kos
    public function updateDocument(Request $request)
    {
        $request->validate([
            'document_file' => 'required|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('document_file')) {
                // Get document file
                $document_file = $request->file('document_file');
    
                // Init boarding service
                $boardingService = new BoardingService();
                $upload = $boardingService->uploadDocument($request->id, $document_file);
    
                if ($upload['error']) {
                    throw new \Exception($upload['message']);
                }

                // Update status dokumen
                $boarding_docs = BoardingDocuments::where('boarding_id', $request->id)->first();
                $boarding_docs->status = 1;
                $boarding_docs->save();
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Dokumen berhasil diperbarui',
                'data' => null
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => $th
            ], 500);
        }
    }
}
