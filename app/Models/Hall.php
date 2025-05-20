<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Hall extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'capacity',
        'training_center_id',
        'description',
        'amenities',
        'is_active',
        'floor_number',
        'room_number'
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
        'capacity' => 'integer'
    ];

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
