@extends('layouts.vertical', ['title' => 'Training Centers', 'sub_title' => 'Show', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <p class="text-sm text-gray-500 dark:text-gray-500">
                        {{ __('Training Center Details') }}
                    </p>
                </div>
                <div class="p-6">
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Name') }}
                            </label>
                            <p class="text-gray-600">{{ $trainingCenter->name }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Status') }}
                            </label>
                            <p class="mt-1">
                                @if ($trainingCenter->status === 'active')
                                    <span class="px-4 py-2 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($trainingCenter->status) }}
                                    </span>
                                @else
                                    <span class="px-4 py-2 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                        {{ ucfirst($trainingCenter->status) }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="lg:col-span-2">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Description') }}
                            </label>
                            <p class="text-gray-600">{{ $trainingCenter->description }}</p>
                        </div>
                    </div>

                    <!-- Images -->
                    {{-- @if ($trainingCenter->hasMedia('training_centers'))
                        <div class="mt-6">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Images') }}
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($trainingCenter->getMedia('training_centers') as $media)
                                    <div class="relative group">
                                        <img src="{{ $media->getUrl('thumb') }}" alt="{{ $media->name }}"
                                            class="w-full h-32 object-cover rounded-lg">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                                class="text-white hover:text-primary">
                                                <i class="fas fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif --}}

                    <!-- Associated Halls -->
                    <div class="mt-6">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                            {{ __('Associated Halls') }}
                        </label>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ __('Image') }}</th>
                                        <th class="text-left">{{ __('Name') }}</th>
                                        <th class="text-left">{{ __('Capacity') }}</th>
                                        <th class="text-left">{{ __('Price Per Hour') }}</th>
                                        <th class="text-left">{{ __('Available') }}</th>
                                        <th class="text-left">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trainingCenter->halls as $hall)
                                        <tr>
                                            <td class="py-2">
                                                @if ($hall->hasMedia('halls'))
                                                    <img src="{{ $hall->getFirstMediaUrl('halls', 'thumb') }}"
                                                        alt="{{ $hall->name }}" class="w-16 h-16 object-cover rounded">
                                                @else
                                                    <div
                                                        class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-2">{{ $hall->name }}</td>
                                            <td class="py-2">{{ $hall->capacity }}</td>
                                            <td class="py-2">{{ number_format($hall->price_per_hour, 2) }}</td>
                                            <td class="py-4">
                                                <div
                                                    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-medium {{ $hall->available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    @if ($hall->available)
                                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ __('Available') }}
                                                    @else
                                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ __('Unavailable') }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('web.halls.show', $hall->id) }}"
                                                        class="btn btn-sm bg-info text-white">
                                                        <i class="mgc_eye_line"></i>
                                                    </a>
                                                    <a href="{{ route('web.halls.edit', $hall->id) }}"
                                                        class="btn btn-sm bg-primary text-white">
                                                        <i class="mgc_edit_line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                {{ __('No halls found for this training center.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Associated Course -->
                    <div class="mt-6">
                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                            {{ __('Associated Courses') }}
                        </label>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ __('Name') }}</th>
                                        <th class="text-left"> {{ __('Category') }}</th>
                                        <th class="text-left">{{ __('Description') }}</th>
                                        <th class="text-left">{{ __('Price') }}</th>
                                        <th class="text-left">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trainingCenter->courses as $course)
                                        <tr>
                                            <td class="py-2">{{ $course->name }}</td>
                                            <td class="py-2">{{ $course->category->name }}</td>
                                            <td class="py-2">{{ Str::limit($course->description, 50) }}</td>
                                            <td class="py-2">{{ $course->pivot->price }}</td>
                                            <td class="py-2">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('web.courses.show', $course->id) }}"
                                                        class="btn btn-sm bg-info text-white">
                                                        <i class="mgc_eye_line"></i>
                                                    </a>
                                                    <a href="{{ route('web.courses.edit', $course->id) }}"
                                                        class="btn btn-sm bg-primary text-white">
                                                        <i class="mgc_edit_line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                {{ __('No courses found for this training center.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('web.training-centers.edit', $trainingCenter->id) }}"
                            class="btn bg-primary text-white">
                            {{ __('Edit Training Center') }}
                        </a>
                        <a href="{{ route('web.training-centers.index') }}" class="btn bg-secondary text-white">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
