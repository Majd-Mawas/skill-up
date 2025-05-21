@extends('layouts.vertical', ['title' => 'Halls', 'sub_title' => 'Show', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <p class="text-sm text-gray-500 dark:text-gray-500">
                        {{ __('Hall Details') }}
                    </p>
                </div>
                <div class="p-6">
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Name') }}
                            </label>
                            <p class="text-gray-600">{{ $hall->name }}</p>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Capacity') }}
                            </label>
                            <p class="text-gray-600">{{ $hall->capacity }}</p>
                        </div>

                        <!-- Price Per Hour -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Price Per Hour') }}
                            </label>
                            <p class="text-gray-600">{{ number_format($hall->price_per_hour, 2) }}</p>
                        </div>

                        <!-- Training Center -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Training Center') }}
                            </label>
                            <p class="text-gray-600">{{ $hall->trainingCenter->name }}</p>
                        </div>

                        <!-- Available -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Available') }}
                            </label>
                            <p class="text-gray-600">
                                @if ($hall->available)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ __('Available') }}</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ __('Not Available') }}</span>
                                @endif
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="lg:col-span-2">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Description') }}
                            </label>
                            <p class="text-gray-600">{{ $hall->description }}</p>
                        </div>
                    </div>

                    <!-- Images -->
                    @if ($hall->hasMedia('halls'))
                        <div class="mt-6">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Images') }}
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($hall->getMedia('halls') as $media)
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
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('web.halls.edit', $hall->id) }}" class="btn bg-primary text-white">
                            {{ __('Edit Hall') }}
                        </a>
                        <a href="{{ route('web.halls.index') }}" class="btn bg-secondary text-white">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lightGallery(document.getElementById('hall-gallery'), {
                selector: 'a',
            });
        });
    </script>
@endpush
