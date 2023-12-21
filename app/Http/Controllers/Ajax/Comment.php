<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouses;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Comment extends Controller
{
    // Menambahkan ulasan baru
    public function store(Request $request)
    {
        $status_code = 500;

        // Validasi data
        $request->validate([
            'boarding_id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email',
            'rating' => 'required|integer',
            'comment' => 'required|string'
        ]);

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Cek apakah user sudah memberikan ulasan
            $check = Comments::where('boarding_id', $request->boarding_id)
                ->where('email', $request->email)
                ->first();
    
            // Throw error jika user sudah memberikan ulasan
            if ($check) {
                $status_code = 400;
                throw new \Exception('Anda sudah memberikan ulasan');
            }
    
            // Store comment
            $comment = new Comments();
            $comment->boarding_id = $request->boarding_id;
            $comment->name = $request->name;
            $comment->email = $request->email;
            $comment->rating = $request->rating;
            $comment->comment = $request->comment;
            $comment->save();

            // Get average rating
            $average_rating = Comments::where('boarding_id', $request->boarding_id)
                ->avg('rating');

            // Update boarding house rating
            $boarding_house = BoardingHouses::find($request->boarding_id);
            $boarding_house->rating = $average_rating;
            $boarding_house->save();
    
            // Commit transaction
            DB::commit();

            // Return response
            return response()->json([
                'error' => false,
                'message' => 'Ulasan berhasil ditambahkan',
                'data' => $comment
            ]);
        } catch (\Throwable $th) {
            // Rollback transaction
            DB::rollBack();

            // Return response
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => null
            ], $status_code);
        }
    }

    // Ringkasan ulasan
    public function getSummary($boarding_id)
    {
        // Mendapatkan rating dan jumlah ulasan
        $rating = BoardingHouses::find($boarding_id)->rating;
        $rating_count = Comments::where('boarding_id', $boarding_id)
            ->count();

        // Return response
        return response()->json([
            'error' => false,
            'message' => 'Berhasil mendapatkan rating',
            'data' => [
                'rating' => number_format($rating, 1, ',', '.'),
                'rating_count' => $rating_count
            ]
        ]);
    }
}
