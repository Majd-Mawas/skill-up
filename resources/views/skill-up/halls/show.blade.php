@extends('layouts.vertical', ['title' => 'Halls', 'sub_title' => 'View', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <h5 class="card-title">
                    Hall NO. {{ $hall->id }}
                </h5>
            </div>
        </div>
        <div class="max-w-5xl mx-auto p-6 rounded shadow">


            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-gray-600">{{ __('Name') }}:
                    <div class="text-lg">{{ $hall->name }}</div>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">{{ __('Capacity') }}:
                    <div class="text-lg">{{ $hall->capacity }}</div>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">{{ __('Price Per Hour') }}:
                    <div class="text-lg">{{ $hall->price_per_hour }}</div>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">{{ __('Created At') }}:
                    <div class="text-lg">{{ $hall->created_at->format('Y-m-d') }}</div>
                    </p>
                </div>
            </div>

            <!-- Description (optional) -->
            @if ($hall->description)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">{{ __('Description') }}</h2>
                    <p class="text-gray-700">{{ $hall->description }}</p>
                </div>
            @endif

            <!-- Images -->
            @if ($hall->hasMedia('halls'))
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">{{ __('Gallery') }}</h2>
                    <div id="hall-gallery" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($hall->getMedia('halls') as $media)
                            <a href="{{ $media->getFullUrl() }}" data-lg-size="1406-1390">
                                <img src="{{ $media->getFullUrl() }}" class="rounded shadow" alt="{{ $media->name }}" />
                            </a>
                        @endforeach
                    </div>

                </div>
            @endif
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
