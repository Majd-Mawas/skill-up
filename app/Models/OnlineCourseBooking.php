<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseBooking extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'trainer_id',
        'start_date',
        'payment_status',
        'booking_status',
        'total_price',
        'notes',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'total_price' => 'decimal:2',
    ];
    
    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the course that is booked.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Get the trainer associated with the booking.
     */
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}
