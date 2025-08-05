<?php

namespace App\Http\Requests\Api\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp,svg', 'max:1024'],
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->has('slug') && $this->has('name')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }
}
