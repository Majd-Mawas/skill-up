<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hall extends Model
{
    use HasFactory, SoftDeletes;

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
