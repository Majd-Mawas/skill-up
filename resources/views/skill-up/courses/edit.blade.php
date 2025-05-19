@extends('layouts.vertical', ['title' => 'Courses', 'sub_title' => 'Create', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">

            <form action="{{ route('courses.update', ['course' => $course->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Add New Course') }}
                        </p>
                    </div>
                    <div class="p-6">

                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div>
                                <label for="title"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Title</label>
                                <input type="text" id="title" name="title" class="form-input"
                                    value="{{ old('title', $course->title) }}">
                                @error('title')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duration -->
                            <div>
                                <label for="duration_hours" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Duration In Hours') }}
                                </label>
                                <input class="form-input" id="duration_hours" type="number" name="duration_hours"
                                    value="{{ old('duration_hours', $course->duration_hours) }}">
                                @error('duration_hours')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">{{ __('Price') }}</label>
                                <input class="form-input" id="price" type="number" name="price" step="0.1"
                                    value="{{ old('price', $course->price) }}">
                                @error('price')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option hidden disabled selected>Select Your Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Description</label>
                            <textarea rows="4" class="form-textarea ltr:rounded-s-none rtl:rounded-e-none" name="description">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn bg-primary text-white my-2 ms-auto">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
