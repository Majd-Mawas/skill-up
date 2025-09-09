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
                    <!-- Center Logo -->
                    @if ($trainingCenter->getFirstMediaUrl('logo'))
                        <div class="mb-6 flex justify-center lg:justify-start">
                            <img src="{{ $trainingCenter->getFirstMediaUrl('logo', 'medium') }}"
                                alt="{{ $trainingCenter->name }} Logo"
                                class="h-32 w-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                        </div>
                    @endif

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

                    <!-- Course Bookings -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('Course Bookings') }}
                            </h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $trainingCenter->courseBookings->count() }} {{ __('Bookings') }}
                            </span>
                        </div>
                        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Student') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Course') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Start Date') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Payment Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Booking Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Total Price') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($trainingCenter->courseBookings as $booking)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->user->name }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->course->name }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->start_date->format('Y-m-d') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($booking->booking_status) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ number_format($booking->total_price, 2) }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="#"
                                                        class="btn btn-sm bg-info text-white hover:bg-info-600 transition-colors duration-200">
                                                        <i class="mgc_eye_line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7"
                                                class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <i class="mgc_file_forbid_line text-3xl mb-2 text-gray-400"></i>
                                                    {{ __('No course bookings found for this training center.') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Placement Tests -->
                    <!-- Placement Test Bookings -->
                    <div class="mt-8 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('Placement Test Bookings') }}
                            </h3>
                            <span
                                class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                {{ $trainingCenter->placementTestBookings->count() }} {{ __('Bookings') }}
                            </span>
                        </div>
                        <div
                            class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow transition-shadow duration-300">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Student') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Test Type') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Booking Time') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Payment Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Booking Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($trainingCenter->placementTestBookings as $booking)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            {{-- @each($booking->booking_time) --}}
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $booking->user->name }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->placementTest->name }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->booking_time }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                    {{ ucfirst($booking->payment_status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($booking->booking_status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($booking->booking_status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                                    {{ ucfirst($booking->booking_status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="#"
                                                        class="btn btn-sm bg-info text-white hover:bg-info-600 transition-colors duration-200">
                                                        <i class="mgc_eye_line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center justify-center space-y-2">
                                                    <i
                                                        class="mgc_calendar_line text-4xl text-gray-400 dark:text-gray-600"></i>
                                                    <p>{{ __('No placement test bookings found for this training center.') }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ICDL Test Bookings -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('ICDL Test Bookings') }}
                            </h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $trainingCenter->icdlTestBookings->count() }} {{ __('Bookings') }}
                            </span>
                        </div>
                        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Student') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Test Type') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Booking Time') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Payment Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Booking Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Total Price') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($trainingCenter->icdlTestBookings as $booking)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->user->name }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->test_type }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->booking_time->format('Y-m-d H:i') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($booking->booking_status) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ number_format($booking->total_price, 2) }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="#"
                                                        class="btn btn-sm bg-info text-white hover:bg-info-600 transition-colors duration-200">
                                                        <i class="mgc_eye_line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7"
                                                class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <i class="mgc_file_forbid_line text-3xl mb-2 text-gray-400"></i>
                                                    {{ __('No ICDL test bookings found for this training center.') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ICDL Card Bookings -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('ICDL Card Bookings') }}
                            </h3>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-teal-100 text-teal-800">
                                {{ $trainingCenter->icdlCardBookings->count() }} {{ __('Bookings') }}
                            </span>
                        </div>
                        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Student') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Full Name (English)') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Booking Time') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Payment Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Booking Status') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Total Price') }}</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($trainingCenter->icdlCardBookings as $booking)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->user->name }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->full_name_english }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $booking->booking_time->format('Y-m-d H:i') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $booking->booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($booking->booking_status) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ number_format($booking->total_price, 2) }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="#"
                                                        class="btn btn-sm bg-info text-white hover:bg-info-600 transition-colors duration-200">
                                                        <i class="mgc_eye_line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7"
                                                class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <i class="mgc_file_forbid_line text-3xl mb-2 text-gray-400"></i>
                                                    {{ __('No ICDL card bookings found for this training center.') }}
                                                </div>
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
