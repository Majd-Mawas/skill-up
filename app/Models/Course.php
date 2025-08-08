<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Course extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'duration_hours',
        'difficulty_level',
        'prerequisites',
        'learning_outcomes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'learning_outcomes' => 'array',
        'prerequisites' => 'array',
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

    /**
     * Define media collections for the course.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('materials')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    /**
     * Define media conversions.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('large')
            ->width(800)
            ->height(600)
            ->sharpen(10);
    }

    /**
     * Get the course's thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    /**
     * Get the course's thumbnail thumb URL.
     */
    public function getThumbnailThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnail', 'thumb');
    }

    /**
     * Get the course's thumbnail medium URL.
     */
    public function getThumbnailMediumUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnail', 'medium');
    }


    /**
     * Scope for filtering by category.
     */
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug)->orWhere('name', 'like', '%' . $categorySlug . '%');
        });
    }

    /**
     * Scope for searching courses.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }
}
