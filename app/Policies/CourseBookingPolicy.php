<?php

namespace App\Policies;

use App\Models\CourseBooking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseBookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view their bookings
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseBooking  $courseBooking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CourseBooking $courseBooking)
    {
        return $user->id === $courseBooking->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true; // All authenticated users can create bookings
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseBooking  $courseBooking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CourseBooking $courseBooking)
    {
        return $user->id === $courseBooking->user_id && $courseBooking->booking_status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CourseBooking  $courseBooking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CourseBooking $courseBooking)
    {
        return $user->id === $courseBooking->user_id && in_array($courseBooking->booking_status, ['pending', 'confirmed']);
    }
}