<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementTestRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'placement_test_id',
        'course_id',
        'recommendation_level',
        'notes',
    ];

    public function placementTest()
    {
        return $this->belongsTo(PlacementTest::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
