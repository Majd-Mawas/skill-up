<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasPhoneVerification;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasPhoneVerification, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'study',
        'phone_number',
        'password',
        'area_id',
        'email_verified_at',
        'phone_verified',
        'phone_verification_code',
        'phone_verification_code_expires_at',
        'password_reset_code',
        'password_reset_code_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'phone_verification_code',
        'phone_verification_code_expires_at',
        'password_reset_code',
        'password_reset_code_expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified' => 'boolean',
        'phone_verification_code_expires_at' => 'datetime',
        'password_reset_code_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Define media collections for the user.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Define media conversions for the user.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->performOnCollections('avatar');

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('avatar');
    }

    /**
     * Get the ICDL card bookings for the user.
     */
    public function icdlCardBookings(): HasMany
    {
        return $this->hasMany(ICDLCardBooking::class);
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('avatar');
    }

    /**
     * Get the user's avatar thumbnail URL.
     */
    public function getAvatarThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('avatar', 'thumb');
    }

    /**
     * Get the user's avatar medium URL.
     */
    public function getAvatarMediumUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('avatar', 'medium');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'user_interests');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function placementTests()
    {
        // This relationship needs to be defined differently as placement_tests table doesn't have user_id
        // For now, return an empty collection to prevent SQL errors
        return $this->hasMany(PlacementTest::class, 'training_center_id', 'id')->whereNull('id');
    }

    public function evaluatedPlacementTests()
    {
        return $this->hasMany(PlacementTest::class, 'evaluator_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'trainer_id');
    }

    public function hallBookings()
    {
        return $this->hasMany(HallBooking::class);
    }

    public function onlineCourseBookings()
    {
        return $this->hasMany(OnlineCourseBooking::class);
    }

    public function onlineCourses()
    {
        return $this->belongsToMany(Course::class, 'course_trainer')
            ->withPivot(['price', 'start_date', 'end_date', 'notes'])
            ->withTimestamps();
    }
}
