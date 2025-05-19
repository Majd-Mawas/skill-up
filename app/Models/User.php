<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasPhoneVerification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPhoneVerification, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
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
        return $this->hasMany(PlacementTest::class);
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
}
