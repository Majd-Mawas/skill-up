@extends('layouts.vertical', ['title' => 'Interests', 'sub_title' => 'Create', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Interest</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('web.interests.store') }}" method="POST" class="mx-4 my-4">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-input" id="name" name="name"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-input" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categories" class="form-label">Categories</label>
                            <select class="form-select" id="categories" name="categories[]" multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categories')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox rounded" id="is_active" name="is_active"
                                    value="1" checked>
                                <label for="is_active" class="ms-2">Active</label>
                            </div>
                            @error('is_active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('web.interests.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
