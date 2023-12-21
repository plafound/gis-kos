<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingHouses extends Model
{
    use HasFactory;

    protected $table = 'boarding_houses';
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'district_id',
        'postal_code',
        'latitude',
        'longitude',
        'phone_number',
        'type',
        'capacity',
        'filled_capacity',
        'price',
        'rating',
        'is_active',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'district_id' => 'integer',
        'postal_code' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'phone_number' => 'string',
        'type' => 'integer',
        'capacity' => 'integer',
        'filled_capacity' => 'integer',
        'price' => 'double',
        'rating' => 'double',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function facilities()
    {
        return $this->hasMany(BoardingFacilities::class, 'boarding_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(BoardingImages::class, 'boarding_id', 'id');
    }

    public function document()
    {
        return $this->hasOne(BoardingDocuments::class, 'boarding_id', 'id');
    }

    public function district()
    {
        return $this->hasOne(Districts::class, 'id', 'district_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'boarding_id', 'id');
    }
}
