<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'centroid',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function trainingCenters()
    {
        return $this->hasMany(TrainingCenter::class);
    }

    /** Neighbours this area *points to* (area_id < neighbor_id) */
    public function outgoingNeighbours(): BelongsToMany
    {
        return $this->belongsToMany(
            Area::class,
            'area_neighbors',
            'area_id',
            'neighbor_id'
        )->withTimestamps();
    }

    /** Neighbours that *point to* this area (area_id > neighbor_id) */
    public function incomingNeighbours(): BelongsToMany
    {
        return $this->belongsToMany(
            Area::class,
            'area_neighbors',
            'neighbor_id',
            'area_id'
        )->withTimestamps();
    }

    /** All neighbours, regardless of edge direction */
    public function neighbours(): Attribute
    {
        return Attribute::get(
            fn() =>
            $this->outgoingNeighbours
                ->merge($this->incomingNeighbours)
                ->sortBy('name')
                ->values()
        );
    }
}
