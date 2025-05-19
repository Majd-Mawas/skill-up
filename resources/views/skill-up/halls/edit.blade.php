@extends('layouts.vertical', ['title' => 'Halls', 'sub_title' => 'Update', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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

            <form action="{{ route('halls.update', $hall->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Add New Hall') }}
                        </p>
                    </div>
                    <div class="p-6">

                        <div class="grid lg:grid-cols-2 gap-6 mb-8">
                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">{{ __('Name') }}</label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name', $hall->name) }}">
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
                                    value="{{ old('capacity', $hall->capacity) }}">
                                @error('capacity')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            <div>
                                <label for="price_per_hour" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Price Per Hour') }}
                                </label>
                                <input class="form-input" id="price_per_hour" type="number" name="price_per_hour"
                                    step="0.5" value="{{ old('price_per_hour', $hall->price_per_hour) }}">
                                @error('price_per_hour')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            <!-- Capacity -->
                            <div>
                                <label for="price_per_hour" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                    {{ __('Is Available') }}
                                </label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-checkbox rounded text-primary" id="customCheck1"
                                        name="available" value="{{ old('available', $hall->available) }}">
                                    <label class="ms-1.5" for="customCheck1">Check this checkbox</label>
                                </div>
                            </div>
                        </div>

                        <!-- Halls Media -->

                        <div class="my-5">
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-medium">{{ __('Current Images') }}</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach ($hall->getMedia('halls') as $image)
                                        <div class="relative">
                                            <img src="{{ $image->getFullUrl() }}" class="rounded shadow w-full h-auto"
                                                alt="Hall Image" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Upload New Media -->
                            {{-- <div class="mt-6">
                                <label for="Media" class="text-sm font-medium">{{ __('Upload New Images') }}</label>
                                <input type="file" name="Media[]" id="Media" multiple class="form-input mt-2">
                            </div> --}}

                        </div>

                        <div class="items-center justify-center w-full mt-5">
                            <label for="dropzone-file" class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Upload New Images') }}
                            </label>
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                            class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX.
                                        800x400px)</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" name="media[]" multiple />
                            </label>
                        </div>

                        <button type="submit" class="btn bg-primary text-white my-2 ms-auto">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
