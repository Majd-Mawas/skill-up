<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Hall;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hall_id' => ['required', 'exists:halls,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'attendees_count' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string']
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
            // $this->validateHallCapacity($validator);

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
        $hallId = $this->input('hall_id');
        $date = $this->input('date');
        $startTime = $this->input('start_time');
        $endTime = $this->input('end_time');

        // Check for overlapping bookings
        $overlappingBookings = Booking::where('hall_id', $hallId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
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
                'hall_id',
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
        $hallId = $this->input('hall_id');
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
        $startTime = $this->input('start_time');
        $endTime = $this->input('end_time');

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
