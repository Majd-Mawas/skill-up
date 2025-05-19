<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'hall_id',
        'trainer_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'attendance')
            ->where('role', 'student')
            ->withPivot(['status', 'attended_at'])
            ->withTimestamps();
    }
}
