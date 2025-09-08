<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ICDLCard extends Model
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
        'price',
        'training_center_id',
        'is_active',
    ];
    
    /**
     * Get the training center that owns the ICDL card.
     */
    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }
    
    /**
     * Get the bookings for the ICDL card.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(ICDLCardBooking::class);
    }
}
