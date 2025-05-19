@extends('layouts.vertical', ['title' => 'Categories', 'sub_title' => 'Create', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">

            <form action="{{ route('categories.store') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Add New Category') }}
                        </p>
                    </div>
                    <div class="p-6">

                        <div class="grid gap-6">
                            <!-- Title -->
                            <div>
                                <label for="name"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Name</label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        {{-- <div>
                            <label for="description"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Description</label>
                            <textarea rows="4" class="form-textarea ltr:rounded-s-none rtl:rounded-e-none" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        <button type="submit" class="btn bg-primary text-white my-2 ms-auto">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
