<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone_number' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('users', 'phone_number')->ignore($userId)
            ],
            'gender' => ['sometimes', 'required', 'string', 'in:male,female'],
            'study' => ['sometimes', 'required', 'string', 'max:255'],
            'interests' => ['sometimes', 'required', 'array', 'min:1'],
            'interests.*' => ['required', 'integer', 'exists:interests,id'],
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
            'phone_number.unique' => 'This phone number is already taken.',
            'phone_number.regex' => 'Please enter a valid phone number.',
            'gender.in' => 'Gender must be either male or female.',
            'interests.required' => 'Please select at least one interest.',
            'interests.min' => 'Please select at least one interest.',
            'interests.*.exists' => 'One or more selected interests are invalid.',
        ];
    }
}
