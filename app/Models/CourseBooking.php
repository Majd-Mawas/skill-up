<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'training_center_id',
        'start_date',
        'payment_status',
        'booking_status',
        'total_price',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'total_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function trainingCenter()
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
