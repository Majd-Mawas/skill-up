<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ICDLCardBooking extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'i_c_d_l_card_id',
        'training_center_id',
        'payment_status',
        'booking_status',
        'total_price',
        'notes',
        'booking_time',
        'full_name_arabic',
        'full_name_english',
        'birth_date',
        'national_id',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_time' => 'datetime',
        'birth_date' => 'date',
    ];
    
    /**
     * Register media collections for the model.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('personal_photo')
             ->singleFile();
             
        $this->addMediaCollection('id_front_photo')
             ->singleFile();
             
        $this->addMediaCollection('id_back_photo')
             ->singleFile();
    }
    
    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the ICDL card that is booked.
     */
    public function icdlCard(): BelongsTo
    {
        return $this->belongsTo(ICDLCard::class, 'i_c_d_l_card_id');
    }
    
    /**
     * Get the training center where the booking is made.
     */
    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }
}
