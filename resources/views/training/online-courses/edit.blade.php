@extends('layouts.vertical', ['title' => 'Edit Online Course', 'sub_title' => 'Update', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h4 class="card-title text-lg font-semibold">Edit Online Course: {{ $onlineCourse->name }}</h4>
                        <div class="flex gap-2">
                            <a href="{{ route('web.online-courses.show', ['online_course' => $onlineCourse->id]) }}"
                                class="btn btn-sm btn-info flex items-center gap-1"><i class="mgc_eye_line text-base"></i>
                                View Details</a>
                            <a href="{{ route('web.online-courses.index') }}"
                                class="btn btn-sm btn-secondary flex items-center gap-1"><i
                                    class="mgc_arrow_left_line text-base"></i> Back to List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('web.online-courses.update', ['online_course' => $onlineCourse->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mx-4 my-4">
                            <div>
                                <div class="mb-4">
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course
                                        Name</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $onlineCourse->name) }}"
                                        class="form-input w-full rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                                        required>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="category_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                    <select name="category_id" id="category_id"
                                        class="form-select w-full rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('category_id') border-red-500 @enderror"
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $onlineCourse->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                        class="form-textarea w-full rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('description') border-red-500 @enderror">{{ old('description', $onlineCourse->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="online_price"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Online Price
                                        (SP)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">SP</span>
                                        </div>
                                        <input type="number" step="0.01" name="online_price" id="online_price"
                                            value="{{ old('online_price', $onlineCourse->online_price) }}"
                                            class="form-input pl-12 w-full rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('online_price') border-red-500 @enderror"
                                            required>
                                    </div>
                                    @error('online_price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <label for="trainers"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trainers</label>
                                    <select name="trainers[]" id="trainers" multiple
                                        class="form-multiselect w-full rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('trainers') border-red-500 @enderror">
                                        @foreach ($trainers as $trainer)
                                            <option value="{{ $trainer->id }}"
                                                {{ in_array($trainer->id, old('trainers', $onlineCourse->trainers->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $trainer->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple trainers</p>
                                    @error('trainers')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="prerequisites"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prerequisites
                                        (one per line)</label>
                                    <textarea name="prerequisites" id="prerequisites" rows="3"
                                        class="form-textarea w-full rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('prerequisites') border-red-500 @enderror">{{ old('prerequisites', is_array($onlineCourse->prerequisites) ? implode("\n", $onlineCourse->prerequisites) : $onlineCourse->prerequisites) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Enter each prerequisite on a new line</p>
                                    @error('prerequisites')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="learning_outcomes"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Learning
                                        Outcomes (one per line)</label>
                                    <textarea name="learning_outcomes" id="learning_outcomes" rows="3"
                                        class="form-textarea w-full rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('learning_outcomes') border-red-500 @enderror">{{ old('learning_outcomes', is_array($onlineCourse->learning_outcomes) ? implode("\n", $onlineCourse->learning_outcomes) : $onlineCourse->learning_outcomes) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Enter each learning outcome on a new line</p>
                                    @error('learning_outcomes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="image"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course
                                        Image</label>
                                    @if ($onlineCourse->getFirstMediaUrl('course_image'))
                                        <div class="mb-3">
                                            <img src="{{ $onlineCourse->getFirstMediaUrl('course_image') }}"
                                                alt="{{ $onlineCourse->name }}"
                                                class="h-32 w-auto object-cover rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">
                                        </div>
                                    @endif
                                    <div class="mt-1 flex items-center">
                                        <input type="file" name="image" id="image" accept="image/*"
                                            class="py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 @error('image') border-red-500 @enderror">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                                    @error('image')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="btn btn-primary flex items-center gap-1 ml-auto"><i
                                    class="mgc_save_line text-base"></i> Update Online Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any JavaScript components here if needed
        });
    </script>
@endsection
