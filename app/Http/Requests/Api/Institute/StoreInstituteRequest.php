<?php

namespace App\Http\Requests\Api\Institute;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstituteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'area_id' => ['required', 'exists:areas,id'],
            'status' => ['required', 'in:active,inactive'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
        ];
    }
}
