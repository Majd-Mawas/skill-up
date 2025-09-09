@extends('layouts.vertical', ['title' => 'Trainers', 'sub_title' => 'Edit', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Edit Trainer') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('web.trainers.update', $trainer) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mx-4 my-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Name') }}</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $trainer->name) }}"
                                    class="form-input @error('name') border-red-500 @enderror" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Email') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $trainer->email) }}"
                                    class="form-input @error('email') border-red-500 @enderror" required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Password') }}</label>
                                <input type="password" id="password" name="password"
                                    class="form-input @error('password') border-red-500 @enderror" placeholder="Leave blank to keep current password">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Phone Number') }}</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $trainer->phone_number) }}"
                                    class="form-input @error('phone_number') border-red-500 @enderror" required>
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Gender') }}</label>
                                <select id="gender" name="gender" class="form-select @error('gender') border-red-500 @enderror" required>
                                    <option value="">{{ __('Select Gender') }}</option>
                                    <option value="male" {{ old('gender', $trainer->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="female" {{ old('gender', $trainer->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Study -->
                            <div>
                                <label for="study" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Study/Specialization') }}</label>
                                <input type="text" id="study" name="study" value="{{ old('study', $trainer->study) }}"
                                    class="form-input @error('study') border-red-500 @enderror">
                                @error('study')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Area -->
                            <div>
                                <label for="area_id" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Area') }}</label>
                                <select id="area_id" name="area_id" class="form-select @error('area_id') border-red-500 @enderror">
                                    <option value="">{{ __('Select Area') }}</option>
                                    @foreach (\App\Models\Area::all() as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', $trainer->area_id) == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Avatar -->
                            <div class="md:col-span-2">
                                <label for="avatar" class="block text-sm font-medium mb-2 dark:text-white">{{ __('Avatar') }}</label>
                                @if($trainer->getFirstMediaUrl('avatar'))
                                    <div class="mb-3">
                                        <img src="{{ $trainer->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $trainer->name }}" class="h-20 w-20 rounded-full object-cover">
                                    </div>
                                @endif
                                <input type="file" id="avatar" name="avatar"
                                    class="form-input @error('avatar') border-red-500 @enderror" accept="image/*">
                                @error('avatar')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('web.trainers.index') }}"
                                class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:border-gray-300 hover:text-gray-600 dark:border-gray-700 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                class="ml-3 py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
