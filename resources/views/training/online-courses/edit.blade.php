@extends('layouts.vertical', ['title' => 'Edit Online Course', 'sub_title' => 'Update', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h4 class="card-title">Edit Online Course: {{ $course->name }}</h4>
                        <div>
                            <a href="{{ route('web.online-courses.show', ['online_course' => $course->id]) }}" class="btn btn-sm btn-secondary">View Details</a>
                            <a href="{{ route('web.online-courses.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('web.online-courses.update', ['online_course' => $course->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $course->name) }}"
                                        class="form-input @error('name') border-red-500 @enderror" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                    <select name="category_id" id="category_id"
                                        class="form-select @error('category_id') border-red-500 @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                    <textarea name="description" id="description" rows="4"
                                        class="form-textarea @error('description') border-red-500 @enderror">{{ old('description', $course->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="online_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Online Price (KD)</label>
                                    <input type="number" step="0.01" name="online_price" id="online_price"
                                        value="{{ old('online_price', $course->online_price) }}"
                                        class="form-input @error('online_price') border-red-500 @enderror" required>
                                    @error('online_price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <label for="trainers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trainers</label>
                                    <select name="trainers[]" id="trainers" multiple
                                        class="form-multiselect @error('trainers') border-red-500 @enderror">
                                        @foreach ($trainers as $trainer)
                                            <option value="{{ $trainer->id }}" {{ in_array($trainer->id, old('trainers', $course->trainers->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $trainer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('trainers')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="prerequisites" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prerequisites (one per line)</label>
                                    <textarea name="prerequisites" id="prerequisites" rows="3"
                                        class="form-textarea @error('prerequisites') border-red-500 @enderror">{{ old('prerequisites', is_array($course->prerequisites) ? implode("\n", $course->prerequisites) : $course->prerequisites) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Enter each prerequisite on a new line</p>
                                    @error('prerequisites')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="learning_outcomes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Learning Outcomes (one per line)</label>
                                    <textarea name="learning_outcomes" id="learning_outcomes" rows="3"
                                        class="form-textarea @error('learning_outcomes') border-red-500 @enderror">{{ old('learning_outcomes', is_array($course->learning_outcomes) ? implode("\n", $course->learning_outcomes) : $course->learning_outcomes) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Enter each learning outcome on a new line</p>
                                    @error('learning_outcomes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Image</label>
                                    @if($course->getFirstMediaUrl('course_image'))
                                        <div class="mb-2">
                                            <img src="{{ $course->getFirstMediaUrl('course_image') }}" alt="{{ $course->name }}" class="h-32 w-auto object-cover rounded">
                                        </div>
                                    @endif
                                    <input type="file" name="image" id="image"
                                        class="form-input @error('image') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                                    @error('image')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="btn btn-primary">Update Online Course</button>
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
