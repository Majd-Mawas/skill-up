@extends('layouts.vertical', ['title' => 'Categories', 'sub_title' => 'Create', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <form action="{{ route('web.categories.store') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New Category</h4>
                    </div>
                    <div class="p-6">
                        <div class="grid gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Name</label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name') }}" placeholder="Enter category name">
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Description</label>
                                <textarea rows="4" class="form-textarea" name="description" placeholder="Enter category description">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('web.categories.index') }}"
                                class="btn border-primary text-primary hover:bg-primary hover:text-white me-2">Cancel</a>
                            <button type="submit" class="btn bg-primary text-white">Create Category</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
