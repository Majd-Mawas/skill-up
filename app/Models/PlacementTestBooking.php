<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementTestBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'placement_test_id',
        'booking_id',
        'user_id',
        'booking_time'

    ];

    public function placementTest()
    {
        return $this->belongsTo(PlacementTest::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
