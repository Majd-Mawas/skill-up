@extends('layouts.vertical', ['title' => 'Courses', 'sub_title' => 'Edit', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <form action="{{ route('web.courses.update', ['course' => $course->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Edit Course') }}
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Name</label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name', $course->name) }}">
                                @error('name')
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
                        <div class="mt-6">
                            <label for="description"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Description</label>
                            <textarea rows="4" class="form-textarea ltr:rounded-s-none rtl:rounded-e-none" name="description">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn bg-primary text-white my-2 ms-auto">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
