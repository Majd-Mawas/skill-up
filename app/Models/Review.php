<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'training_center_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'float',
        'is_verified' => 'boolean'
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

    // Helper method to get the average rating for a course at a specific training center
    public static function getAverageRatingForCourseAtTrainingCenter($courseId, $trainingCenterId)
    {
        return static::where('course_id', $courseId)
            ->where('training_center_id', $trainingCenterId)
            ->avg('rating');
    }

    // Helper method to get the average rating for a training center
    public static function getAverageRatingForTrainingCenter($trainingCenterId)
    {
        return static::where('training_center_id', $trainingCenterId)
            ->avg('rating');
    }
}
