@extends('layouts.vertical', ['title' => 'Trainers', 'sub_title' => 'Details', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h4 class="card-title">{{ __('Trainer Details') }}</h4>
                    <div class="flex gap-2">
                        <a href="{{ route('web.trainers.edit', $trainer) }}"
                            class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            <i class="mgc_edit_line text-lg"></i>
                            {{ __('Edit') }}
                        </a>
                        <a href="{{ route('web.trainers.index') }}"
                            class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:border-gray-300 hover:text-gray-600 dark:border-gray-700 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600">
                            <i class="mgc_arrow_left_line text-lg"></i>
                            {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-4">
                        <div class="md:col-span-1">
                            <div class="flex flex-col items-center">
                                <div class="mb-4">
                                    @if ($trainer->getFirstMediaUrl('avatar'))
                                        <img src="{{ $trainer->getFirstMediaUrl('avatar', 'medium') }}"
                                            alt="{{ $trainer->name }}" class="h-32 w-32 rounded-full object-cover">
                                    @else
                                        <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-4xl text-gray-500">{{ substr($trainer->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-xl font-semibold">{{ $trainer->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Trainer') }}</p>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}
                                    </h5>
                                    <p class="text-base font-medium">{{ $trainer->email }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Phone Number') }}</h5>
                                    <p class="text-base font-medium">{{ $trainer->phone_number }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Gender') }}
                                    </h5>
                                    <p class="text-base font-medium">{{ ucfirst($trainer->gender) }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Study/Specialization') }}</h5>
                                    <p class="text-base font-medium">{{ $trainer->study ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Area') }}
                                    </h5>
                                    <p class="text-base font-medium">{{ $trainer->area->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('Joined Date') }}</h5>
                                    <p class="text-base font-medium">{{ $trainer->created_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Courses Section -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4  mx-4">{{ __('Assigned Courses') }}</h4>
                        @if (isset($trainer->onlineCourses) && $trainer->onlineCourses->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                {{ __('Course Name') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                {{ __('Category') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                {{ __('Duration') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                {{ __('Status') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($trainer->onlineCourses as $course)
                                            <tr>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    {{ $course->name }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    {{ $course->category->name ?? 'N/A' }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    {{ $course->duration_hours }} {{ __('hours') }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                        {{ $course->is_active ? __('Active') : __('Inactive') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                {{ __('No courses assigned to this trainer yet.') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
