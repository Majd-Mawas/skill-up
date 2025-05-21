<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Hall extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'price_per_hour',
        'available',
        'training_center_id',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'price_per_hour' => 'decimal:2',
        'available' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('halls')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(400)
            ->sharpen(10);
    }

    public function trainingCenter()
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
