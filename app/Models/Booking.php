<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'hall_id',
        'user_id',
        'start_time',
        'end_time',
        'date',
        'purpose',
        'status',
        'notes',
        'attendees_count'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
        'attendees_count' => 'integer'
    ];

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
