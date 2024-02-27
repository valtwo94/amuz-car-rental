<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'year',
        'price',
        'company',
        'mileage',
        'imageUrl',
        'status',
    ];

    protected $table = 'cars';

    protected $primaryKey = 'id';

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
