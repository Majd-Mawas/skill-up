<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function trainingCenters()
    {
        return $this->belongsToMany(TrainingCenter::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function placementTestRecommendations()
    {
        return $this->hasMany(PlacementTestRecommendation::class);
    }

    /**
     * Get the levels for the course.
     */
    public function levels()
    {
        return $this->hasMany(CourseLevel::class)->ordered();
    }

    /**
     * Get the first level of the course.
     */
    public function firstLevel()
    {
        return $this->levels()->orderBy('level_order')->first();
    }

    /**
     * Get the last level of the course.
     */
    public function lastLevel()
    {
        return $this->levels()->orderByDesc('level_order')->first();
    }
}
