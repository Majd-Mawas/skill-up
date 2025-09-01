<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'slug',
        // 'sort_order',
    ];

    protected $casts = [
        // 'sort_order' => 'integer',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the interests associated with this category.
     */
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'categories_interests');
    }

    /**
     * Define media collections for the category.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']);
    }

    /**
     * Define media conversions.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(64)
            ->height(64)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(128)
            ->height(128)
            ->sharpen(10);
    }

    /**
     * Get the category's icon URL.
     */
    public function getIconUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('icon');
    }

    /**
     * Get the category's icon thumb URL.
     */
    public function getIconThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('icon', 'thumb');
    }

    /**
     * Get the category's icon medium URL.
     */
    public function getIconMediumUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('icon', 'medium');
    }

    /**
     * Scope for ordered categories.
     */
    public function scopeOrdered($query)
    {
        return $query
            // ->orderBy('sort_order')
            ->orderBy('name');
    }
}
