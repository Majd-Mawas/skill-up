<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TrainingCenter extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'area_id',
        'status',
        'website',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function halls()
    {
        return $this->hasMany(Hall::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    public function instructors()
    {
        return $this->hasMany(User::class)->where('role', 'instructor');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Define media collections for the training center.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
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
    }

    /**
     * Get the training center's logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('logo');
    }

    /**
     * Get the training center's logo thumb URL.
     */
    public function getLogoThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('logo', 'thumb');
    }

    /**
     * Get the training center's logo medium URL.
     */
    public function getLogoMediumUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('logo', 'medium');
    }

    /**
     * Scope for active training centers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for searching by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }
}
