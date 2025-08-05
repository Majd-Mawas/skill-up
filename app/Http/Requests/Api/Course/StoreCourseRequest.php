<?php

namespace App\Http\Requests\Api\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'duration_hours' => ['nullable', 'integer', 'min:1'],
            'difficulty_level' => ['nullable', 'in:beginner,intermediate,advanced'],
            'prerequisites' => ['nullable', 'array'],
            'learning_outcomes' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
        ];
    }
}