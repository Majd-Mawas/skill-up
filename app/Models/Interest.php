<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that have this interest.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }

    /**
     * Scope a query to only include active interests.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
