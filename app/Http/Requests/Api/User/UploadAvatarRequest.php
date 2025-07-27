<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class UploadAvatarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => [
                'required',
                'image',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'avatar.required' => 'Please select an image file.',
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'The image must be a JPEG, PNG, or WebP file.',
            'avatar.max' => 'The image size must not exceed 2MB.',
            'avatar.dimensions' => 'The image must be between 100x100 and 2000x2000 pixels.',
        ];
    }
}
