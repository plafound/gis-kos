<?php

namespace App\Services;

use App\Models\BoardingDocuments;
use App\Models\BoardingHouses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BoardingService
{
    /**
     * Get all boarding houses
     * 
     * @param  array $filters
     * @return array
     * @throws \Throwable
     */
    public function get(array $filters = [])
    {
        $status_code = 500;

        try {
            $data = BoardingHouses::with('district');

            if (empty($filters)) {
                $data = $data->where('is_active', true)->get();
            } else {
                $data = $data->select(
                    'boarding_houses.id',
                    'boarding_houses.name',
                    'boarding_houses.address',
                    'boarding_houses.district_id',
                    'boarding_houses.postal_code',
                    'boarding_houses.latitude',
                    'boarding_houses.longitude',
                    'boarding_houses.phone_number',
                    'boarding_houses.type',
                    'boarding_houses.price'
                );

                if (count($filters['facilities']) > 0) {
                    $data = $data->join('boarding_has_facilities', 'boarding_houses.id', '=', 'boarding_has_facilities.boarding_id')
                        ->whereIn('boarding_has_facilities.facility_id', $filters['facilities']);
                }

                if ($filters['price']['min'] > 0) {
                    $data = $data->where('boarding_houses.price', '>=', $filters['price']['min']);
                }

                if ($filters['price']['max'] > 0) {
                    $data = $data->where('boarding_houses.price', '<=', $filters['price']['max']);
                }

                if (count($filters['facilities']) > 0) {
                    $data = $data->groupBy(
                        'boarding_houses.id',
                        'boarding_houses.name',
                        'boarding_houses.address',
                        'boarding_houses.district_id',
                        'boarding_houses.postal_code',
                        'boarding_houses.latitude',
                        'boarding_houses.longitude',
                        'boarding_houses.phone_number',
                        'boarding_houses.type',
                        'boarding_houses.price'
                    )
                    ->havingRaw('COUNT(DISTINCT boarding_has_facilities.facility_id) = ' . count($filters['facilities']));
                }

                if ($filters['type'] != "all") {
                    $data = $data->where('type', $filters['type']);
                }

                if ($filters['district'] != "all") {
                    $data = $data->where('district_id', $filters['district']);
                }

                $data = $data->where('is_active', true);
                
                $data = $data->get();
            }

            return [
                'status_code' => 200,
                'error' => false,
                'data' => $data
            ];
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . " | on line: " . $th->getLine() . " | " . $th->getMessage());
            
            return [
                'status_code' => $status_code,
                'error' => true,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Upload user document
     * 
     * @param  int $id
     * @param  \Illuminate\Http\UploadedFile $document_file
     * @return array
     * @throws \Throwable
     */
    public function uploadDocument($id, $document_file)
    {
        $status_code = 500;

        DB::beginTransaction();
        try {
            // Init document file name
            $document_file_name = "";

            // Check if boarding house has document
            $is_document_exist = BoardingDocuments::where('boarding_id', $id)->first();
            if ($is_document_exist) {
                // Delete old document file
                $old_document_path = $is_document_exist->document_path;

                if (!empty($old_document_path)) {
                    $old_document_file = public_path($old_document_path);
                    if (file_exists($old_document_file)) {
                        unlink($old_document_file);
                    }
                }
            }

            // Upload document file
            if (!empty($document_file)) {
                $document_file_name = time() . '_' . $document_file->getClientOriginalName();
                $document_file->move(public_path('uploads/documents'), $document_file_name);
            }

            // Save document file name to database
            $data = BoardingDocuments::updateOrCreate(
                [
                    'boarding_id' => $id,
                ],
                [
                    'document_path' => 'uploads/documents/' . $document_file_name,
                ]
            );

            // Update boarding house status
            BoardingHouses::where('id', $id)->update([
                'is_active' => false
            ]);

            // Commit transaction
            DB::commit();
            return [
                'status_code' => 200,
                'error' => false,
                'data' => $data
            ];
        } catch (\Throwable $th) {
            // Rollback transaction
            DB::rollBack();

            Log::error(__METHOD__ . " | on line: " . $th->getLine() . " | " . $th->getMessage());

            return [
                'status_code' => $status_code,
                'error' => true,
                'message' => $th->getMessage()
            ];
        }
    }
}