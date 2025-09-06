<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow the owner of the booking to update it
        $booking = $this->route('id') ? \App\Models\CourseBooking::find($this->route('id')) : null;
        return $booking ? $booking->user_id === auth()->id() : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'sometimes|required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
