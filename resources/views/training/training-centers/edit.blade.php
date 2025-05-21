@extends('layouts.vertical', ['title' => 'Training Centers', 'sub_title' => 'Edit', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <form action="{{ route('web.training-centers.update', ['training_center' => $trainingCenter->id]) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Edit Training Center') }}
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Name</label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name', $trainingCenter->name) }}">
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Area -->
                            <div>
                                <label for="area_id"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Area</label>
                                <select class="form-select" id="area_id" name="area_id">
                                    <option hidden disabled selected>Select Your Area</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ old('area_id', $trainingCenter->area_id) == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone_number" class="text-gray-800 text-sm font-medium inline-block mb-2">Phone
                                    Number</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-input"
                                    value="{{ old('phone_number', $trainingCenter->phone_number) }}">
                                @error('phone_number')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Email</label>
                                <input type="email" id="email" name="email" class="form-input"
                                    value="{{ old('email', $trainingCenter->email) }}">
                                @error('email')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active"
                                        {{ old('status', $trainingCenter->status) == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $trainingCenter->status) == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                @error('status')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label for="address"
                                class="text-gray-800 text-sm font-medium inline-block mb-2">Address</label>
                            <textarea rows="4" class="form-textarea ltr:rounded-s-none rtl:rounded-e-none" name="address">{{ old('address', $trainingCenter->address) }}</textarea>
                            @error('address')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn bg-primary text-white my-2 ms-auto">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
