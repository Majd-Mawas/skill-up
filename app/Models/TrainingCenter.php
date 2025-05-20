<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'email',
        'area_id',
        'status',
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
}
