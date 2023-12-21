<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingDocuments extends Model
{
    use HasFactory;

    protected $table = 'boarding_documents';
    protected $fillable = [
        'boarding_id',
        'document_path',
        'status',
    ];

    protected $casts = [
        'boarding_id' => 'integer',
        'document_path' => 'string',
        'status' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function boarding_house()
    {
        return $this->hasOne(BoardingHouses::class, 'id', 'boarding_id');
    }
}
