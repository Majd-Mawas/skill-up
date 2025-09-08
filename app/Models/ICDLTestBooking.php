<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ICDLTestBooking extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'i_c_d_l_test_id',
        'training_center_id',
        'payment_status',
        'booking_status',
        'total_price',
        'notes',
        'booking_time',
        'test_type',
        'full_name_arabic',
        'full_name_english',
        'birth_date',
        'national_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_time' => 'datetime',
        'birth_date' => 'date',
    ];
    
    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the ICDL test that is booked.
     */
    public function icdlTest(): BelongsTo
    {
        return $this->belongsTo(ICDLTest::class, 'i_c_d_l_test_id');
    }
    
    /**
     * Get the training center where the booking is made.
     */
    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }
}
