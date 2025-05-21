<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'level_order',
        'is_active',
    ];

    protected $casts = [
        'level_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the course that owns the level.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the next level in the sequence.
     */
    public function nextLevel()
    {
        return $this->course->levels()
            ->where('level_order', '>', $this->level_order)
            ->orderBy('level_order')
            ->first();
    }

    /**
     * Get the previous level in the sequence.
     */
    public function previousLevel()
    {
        return $this->course->levels()
            ->where('level_order', '<', $this->level_order)
            ->orderByDesc('level_order')
            ->first();
    }

    /**
     * Scope a query to only include active levels.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by level order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('level_order');
    }
}
