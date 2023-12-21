<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingFacilities extends Model
{
    use HasFactory;

    protected $table = 'boarding_has_facilities';
    protected $fillable = [
        'boarding_id',
        'facility_id',
    ];

    protected $casts = [
        'boarding_id' => 'integer',
        'facility_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function boarding_house()
    {
        return $this->hasOne(BoardingHouses::class, 'id', 'boarding_id');
    }

    public function facility()
    {
        return $this->hasOne(Facilities::class, 'id', 'facility_id');
    }
}
