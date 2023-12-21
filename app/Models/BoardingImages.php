<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingImages extends Model
{
    use HasFactory;

    protected $table = 'boarding_images';
    protected $fillable = [
        'boarding_id',
        'sequence',
        'image_path',
    ];

    protected $casts = [
        'boarding_id' => 'integer',
        'sequence' => 'integer',
        'image_path' => 'string',
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
