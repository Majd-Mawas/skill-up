<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Hall;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the booking belongs to the authenticated user
        $booking = $this->route('booking');
        return Auth::check() && $booking && $booking->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['sometimes', 'required', 'date', 'after_or_equal:today'],
            'end_date' => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'end_time' => ['sometimes', 'required', 'date_format:H:i', 'after:start_time'],
            'purpose' => ['sometimes', 'required', 'string', 'max:255'],
            'attendees_count' => ['sometimes', 'required', 'integer', 'min:1'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'total_price' => ['sometimes', 'nullable', 'numeric', 'min:0']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if hall is available (not booked for the requested time)
            $this->validateHallAvailability($validator);
            
            // Check if hall capacity is sufficient
            $this->validateHallCapacity($validator);
            
            // Check if booking is within operating hours (8 AM to 10 PM)
            $this->validateOperatingHours($validator);
        });
    }
    
    /**
     * Validate that the hall is available for the requested time.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateHallAvailability($validator)
    {
        $booking = $this->route('booking');
        $hallId = $booking->hall_id;
        
        // Only validate if date or time fields are being updated
        if (!$this->has('start_date') && !$this->has('end_date') && !$this->has('start_time') && !$this->has('end_time')) {
            return;
        }
        
        $startDate = $this->input('start_date', $booking->start_date->format('Y-m-d'));
        $endDate = $this->input('end_date', $booking->end_date->format('Y-m-d'));
        $startTime = $this->input('start_time', $booking->start_time->format('H:i'));
        $endTime = $this->input('end_time', $booking->end_time->format('H:i'));
        
        // Check for overlapping bookings, excluding the current booking
        $overlappingBookings = Booking::where('hall_id', $hallId)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $booking->id) // Exclude current booking
            ->where(function ($query) use ($startDate, $endDate) {
                // Bookings that overlap with our date range
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Existing booking starts during our date range
                    $q->where('start_date', '>=', $startDate)
                      ->where('start_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Existing booking ends during our date range
                    $q->where('end_date', '>=', $startDate)
                      ->where('end_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Existing booking spans our entire date range
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $endDate);
                });
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // New booking starts during an existing booking
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New booking ends during an existing booking
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New booking completely contains an existing booking
                    $q->where('start_time', '>=', $startTime)
                      ->where('end_time', '<=', $endTime);
                });
            })
            ->exists();
        
        if ($overlappingBookings) {
            $validator->errors()->add(
                'start_date',
                'The hall is already booked for the selected time period.'
            );
        }
    }
    
    /**
     * Validate that the hall capacity is sufficient for the requested attendees.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateHallCapacity($validator)
    {
        // Only validate if attendees_count is being updated
        if (!$this->has('attendees_count')) {
            return;
        }
        
        $booking = $this->route('booking');
        $hallId = $booking->hall_id;
        $attendeesCount = $this->input('attendees_count');
        
        $hall = Hall::find($hallId);
        
        if ($hall && $attendeesCount > $hall->capacity) {
            $validator->errors()->add(
                'attendees_count',
                "The number of attendees exceeds the hall's capacity of {$hall->capacity}."
            );
        }
    }
    
    /**
     * Validate that the booking is within operating hours (8 AM to 10 PM).
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateOperatingHours($validator)
    {
        // Only validate if time fields are being updated
        if (!$this->has('start_time') && !$this->has('end_time')) {
            return;
        }
        
        $booking = $this->route('booking');
        $startTime = $this->input('start_time', $booking->start_time->format('H:i'));
        $endTime = $this->input('end_time', $booking->end_time->format('H:i'));
        
        $operatingStart = '08:00';
        $operatingEnd = '22:00';
        
        if ($startTime < $operatingStart) {
            $validator->errors()->add(
                'start_time',
                'Booking cannot start before 8:00 AM.'
            );
        }
        
        if ($endTime > $operatingEnd) {
            $validator->errors()->add(
                'end_time',
                'Booking cannot end after 10:00 PM.'
            );
        }
    }
}