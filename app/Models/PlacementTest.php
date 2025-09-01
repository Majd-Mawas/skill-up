<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_date',
        'score',
        'level',
        'status',
        'notes',
        'evaluator_id',
        'training_center_id',
        'evaluation_date',
        'recommended_courses',
    ];

    protected $casts = [
        'test_date' => 'datetime',
        'evaluation_date' => 'datetime',
        'recommended_courses' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function recommendations()
    {
        return $this->hasMany(PlacementTestRecommendation::class);
    }
    
    public function trainingCenter()
    {
        return $this->belongsTo(TrainingCenter::class);
    }
    
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'placement_test_bookings');
    }
}
