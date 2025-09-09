@extends('layouts.vertical', ['title' => 'User Details', 'sub_title' => 'Profile', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <div class="grid grid-cols-12 gap-6 md:gap-8">
            <!-- User Information Card -->
            <div class="col-span-12 lg:col-span-12">
                <div class="card shadow-sm hover:shadow-md transition-shadow duration-300 sticky top-20 lg:h-fit">
                    <div class="card-header bg-primary-50 py-3 px-4 flex items-center border-b border-primary-100">
                        <i class="mgc_user_line text-xl me-2 text-primary-700"></i>
                        <h4 class="text-lg font-semibold text-primary-700">User Profile</h4>
                    </div>
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            @if ($user->getFirstMediaUrl('avatar'))
                                <img src="{{ $user->getFirstMediaUrl('avatar', 'medium') }}" alt="{{ $user->name }}"
                                    class="rounded-full w-24 h-24 mx-auto mb-4 object-cover ring-4 ring-primary-100">
                            @else
                                <div
                                    class="w-24 h-24 mx-auto mb-4 rounded-full bg-gray-200 flex items-center justify-center ring-4 ring-primary-100">
                                    <span class="text-2xl font-bold text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <h4 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h4>
                            <p class="text-gray-500 mt-1">
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-primary-500 text-white">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="p-3 bg-primary-50 rounded-full">
                                        <i class="mgc_mail_line text-xl text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-400">Email</p>
                                        <p class="text-base font-semibold text-gray-700">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="p-3 bg-primary-50 rounded-full">
                                        <i class="mgc_phone_line text-xl text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-400">Phone</p>
                                        <p class="text-base font-semibold text-gray-700">{{ $user->phone_number ?? 'Not provided' }}</p>
                                    </div>
                                </div>

                                @if ($user->area)
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="p-3 bg-primary-50 rounded-full">
                                        <i class="mgc_map_pin_line text-xl text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-400">Area</p>
                                        <p class="text-base font-semibold text-gray-700">{{ $user->area->name }}</p>
                                    </div>
                                </div>
                                @endif

                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="p-3 bg-primary-50 rounded-full">
                                        <i class="mgc_user_3_line text-xl text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-400">Gender</p>
                                        <p class="text-base font-semibold text-gray-700">{{ ucfirst($user->gender ?? 'Not specified') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="p-3 bg-primary-50 rounded-full">
                                        <i class="mgc_book_2_line text-xl text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-400">Study</p>
                                        <p class="text-base font-semibold text-gray-700">{{ $user->study ?? 'Not provided' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="p-3 bg-primary-50 rounded-full">
                                        <i class="mgc_calendar_line text-xl text-primary-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-400">Joined</p>
                                        <p class="text-base font-semibold text-gray-700">{{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings and Enrollments -->
            <div class="col-span-12 lg:col-span-8">
                <h3 class="text-xl font-bold mb-6 text-gray-800 border-b pb-3 flex items-center"><i
                        class="mgc_bookmark_line text-xl me-2 text-primary-700"></i>Bookings & Enrollments</h3>

                <!-- Course Enrollments -->
                <div class="card mb-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="card-header bg-primary-50 py-3 px-4 flex items-center border-b border-primary-100">
                        <i class="mgc_book_2_line text-xl me-2 text-primary-700"></i>
                        <h4 class="text-lg font-semibold text-primary-700">Course Enrollments</h4>
                    </div>
                    <div class="card-body p-5">
                        @if ($user->enrollments->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="table table-centered table-bordered table-hover w-full">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="py-3">Course</th>
                                            <th class="py-3">Level</th>
                                            <th class="py-3">Status</th>
                                            <th class="py-3">Enrolled On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->enrollments as $enrollment)
                                            <tr>
                                                <td class="py-2">{{ $enrollment->course->name }}</td>
                                                <td class="py-2">{{ $enrollment->course->level ?? 'N/A' }}</td>
                                                <td class="py-2">
                                                    <span
                                                        class="badge {{ $enrollment->status === 'active' ? 'bg-success-500' : 'bg-warning-500' }}">
                                                        {{ ucfirst($enrollment->status) }}
                                                    </span>
                                                </td>
                                                <td class="py-2">{{ $enrollment->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="mgc_book_2_line text-5xl text-gray-400 mb-2"></i>
                                <p class="mt-3 text-gray-500 font-medium">No course enrollments found.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Hall Bookings -->
                <div class="card mb-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="card-header bg-primary-50 py-3 px-4 flex items-center border-b border-primary-100">
                        <i class="mgc_building_4_line text-xl me-2 text-primary-700"></i>
                        <h4 class="text-lg font-semibold text-primary-700">Hall Bookings</h4>
                    </div>
                    <div class="card-body p-5">
                        @if ($user->hallBookings->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="table table-centered table-bordered table-hover w-full">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="py-3">Hall</th>
                                            <th class="py-3">Date</th>
                                            <th class="py-3">Time</th>
                                            <th class="py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->hallBookings as $booking)
                                            <tr>
                                                <td class="py-2">{{ $booking->hall->name }}</td>
                                                <td class="py-2">{{ $booking->date->format('M d, Y') }}</td>
                                                <td class="py-2">{{ $booking->start_time }} -
                                                    {{ $booking->end_time }}</td>
                                                <td class="py-2">
                                                    <span
                                                        class="badge {{ $booking->status === 'confirmed' ? 'bg-success-500' : ($booking->status === 'pending' ? 'bg-warning-500' : 'bg-danger-500') }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="mgc_building_4_line text-5xl text-gray-400 mb-2"></i>
                                <p class="mt-3 text-gray-500 font-medium">No hall bookings found.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Online Courses -->
                <div class="card mb-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="card-header bg-primary-50 py-3 px-4 flex items-center border-b border-primary-100">
                        <i class="mgc_computer_line text-xl me-2 text-primary-700"></i>
                        <h4 class="text-lg font-semibold text-primary-700">Online Courses</h4>
                    </div>
                    <div class="card-body p-5">
                        @if ($user->onlineCourseBookings->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="table table-centered table-bordered table-hover w-full">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="py-3">Course</th>
                                            <th class="py-3">Start Date</th>
                                            <th class="py-3">End Date</th>
                                            <th class="py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->onlineCourseBookings as $booking)
                                            <tr>
                                                <td class="py-2">{{ $booking->course->name }}</td>
                                                <td class="py-2">{{ $booking->start_date->format('M d, Y') }}</td>
                                                <td class="py-2">{{ $booking->end_date->format('M d, Y') }}</td>
                                                <td class="py-2">
                                                    <span
                                                        class="badge {{ $booking->status === 'active' ? 'bg-success-500' : 'bg-warning-500' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="mgc_computer_line text-5xl text-gray-400 mb-2"></i>
                                <p class="mt-3 text-gray-500 font-medium">No online course bookings found.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- ICDL Cards -->
                <div class="card mb-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="card-header bg-primary-50 py-3 px-4 flex items-center border-b border-primary-100">
                        <i class="mgc_id_card_line text-xl me-2 text-primary-700"></i>
                        <h4 class="text-lg font-semibold text-primary-700">ICDL Cards</h4>
                    </div>
                    <div class="card-body p-5">
                        @if ($user->icdlCardBookings->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="table table-centered table-bordered table-hover w-full">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="py-3">Card Number</th>
                                            <th class="py-3">Issue Date</th>
                                            <th class="py-3">Expiry Date</th>
                                            <th class="py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->icdlCardBookings as $booking)
                                            <tr>
                                                <td class="py-2">{{ $booking->icdlCard->card_number }}</td>
                                                <td class="py-2">
                                                    {{ $booking?->icdlCard?->issue_date?->format('M d, Y') ?? null }}
                                                </td>
                                                <td class="py-2">
                                                    {{ $booking?->icdlCard?->expiry_date?->format('M d, Y') ?? null }}
                                                </td>
                                                <td class="py-2">
                                                    <span
                                                        class="badge {{ $booking->status === 'active' ? 'bg-success-500' : 'bg-warning-500' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="mgc_id_card_line text-5xl text-gray-400 mb-2"></i>
                                <p class="mt-3 text-gray-500 font-medium">No ICDL card bookings found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
