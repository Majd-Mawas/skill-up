@extends('layouts.vertical', ['title' => 'Create Online Course', 'sub_title' => 'New', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h4 class="card-title">Create New Online Course</h4>
                        <a href="{{ route('web.online-courses.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('web.online-courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
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
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        class="form-textarea @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="online_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Online Price (KD)</label>
                                    <input type="number" step="0.01" name="online_price" id="online_price"
                                        value="{{ old('online_price') }}"
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
                                            <option value="{{ $trainer->id }}" {{ in_array($trainer->id, old('trainers', [])) ? 'selected' : '' }}>
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
                                        class="form-textarea @error('prerequisites') border-red-500 @enderror">{{ old('prerequisites') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Enter each prerequisite on a new line</p>
                                    @error('prerequisites')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="learning_outcomes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Learning Outcomes (one per line)</label>
                                    <textarea name="learning_outcomes" id="learning_outcomes" rows="3"
                                        class="form-textarea @error('learning_outcomes') border-red-500 @enderror">{{ old('learning_outcomes') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Enter each learning outcome on a new line</p>
                                    @error('learning_outcomes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Image</label>
                                    <input type="file" name="image" id="image"
                                        class="form-input @error('image') border-red-500 @enderror">
                                    <p class="text-xs text-gray-500 mt-1">Recommended size: 800x600 pixels</p>
                                    @error('image')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="btn btn-primary">Create Online Course</button>
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