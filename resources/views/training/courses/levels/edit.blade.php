@extends('layouts.vertical', ['title' => 'Course Levels', 'sub_title' => 'Update', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Edit Level') }}
                        </p>
                        <a href="{{ route('web.courses.levels.index', $course) }}" class="btn bg-secondary text-white">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('web.courses.levels.update', [$course, $level]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Name') }}
                                </label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name', $level->name) }}" required>
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Level Order -->
                            <div>
                                <label for="level_order" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Level Order') }}
                                </label>
                                <input type="number" id="level_order" name="level_order" class="form-input"
                                    value="{{ old('level_order', $level->level_order) }}" min="1" required>
                                @error('level_order')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="lg:col-span-2">
                                <label for="description" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Description') }}
                                </label>
                                <textarea id="description" name="description" class="form-input" rows="4">{{ old('description', $level->description) }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Is Active -->
                            <div>
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Status') }}
                                </label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-checkbox rounded text-primary" id="is_active"
                                        name="is_active" value="1"
                                        {{ old('is_active', $level->is_active) ? 'checked' : '' }}>
                                    <label class="ms-1.5" for="is_active">{{ __('Active') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn bg-primary text-white">
                                {{ __('Update Level') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
