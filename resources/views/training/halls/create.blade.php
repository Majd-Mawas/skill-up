@extends('layouts.vertical', ['title' => 'Halls', 'sub_title' => 'Create', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('web.halls.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Add New Hall') }}
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Name') }}
                                </label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div>
                                <label for="capacity" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Capacity') }}
                                </label>
                                <input class="form-input" id="capacity" type="number" name="capacity"
                                    value="{{ old('capacity') }}">
                                @error('capacity')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price Per Hour -->
                            <div>
                                <label for="price_per_hour" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Price Per Hour') }}
                                </label>
                                <input class="form-input" id="price_per_hour" type="number" name="price_per_hour"
                                    step="0.01" value="{{ old('price_per_hour') }}">
                                @error('price_per_hour')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Training Center -->
                            <div>
                                <label for="training_center_id" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Training Center') }}
                                </label>
                                <select class="form-select" id="training_center_id" name="training_center_id">
                                    @foreach ($trainingCenters as $trainingCenter)
                                        <option value="{{ $trainingCenter->id }}"
                                            {{ old('training_center_id') == $trainingCenter->id ? 'selected' : '' }}>
                                            {{ $trainingCenter->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('training_center_id')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div

                            <!-- Available -->
                            <div>
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Available') }}
                                </label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-checkbox rounded text-primary" id="available"
                                        name="available" value="1" {{ old('available') ? 'checked' : '' }}>
                                    <label class="ms-1.5" for="available">{{ __('Available') }}</label>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="lg:col-span-2">
                                <label for="description" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Description') }}
                                </label>
                                <textarea id="description" name="description" class="form-input" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Upload Images -->
                        <div class="mt-6">
                            <label for="media" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Upload Images') }}
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="media"
                                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">{{ __('Click to upload') }}</span>
                                            {{ __('or drag and drop') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('PNG, JPG or GIF (MAX. 2MB)') }}
                                        </p>
                                    </div>
                                    <input id="media" type="file" class="hidden" name="media[]" multiple
                                        accept="image/jpeg,image/png,image/gif">
                                </label>
                            </div>
                            @error('media')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn bg-primary text-white">
                                {{ __('Create Hall') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
