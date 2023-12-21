<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = [
        'boarding_id',
        'name',
        'email',
        'rating',
        'comment'
    ];

    protected $casts = [
        'boarding_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'rating' => 'integer',
        'comment' => 'string'
    ];

    public function boarding()
    {
        return $this->hasOne(BoardingHouses::class, 'id', 'boarding_id');
    }
}
