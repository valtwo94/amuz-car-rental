<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'car_id',
        'user_id',
        'confirmation_time',
        'status',
        'pickup_region',
        'pay_method',
        'pickup_time',
        'cancellation_detail',
        'total_price'
    ];

    // Relationships
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
