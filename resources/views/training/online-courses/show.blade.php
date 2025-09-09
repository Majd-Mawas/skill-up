@extends('layouts.vertical', ['title' => 'Online Course Details', 'sub_title' => 'View', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <h4 class="card-title">{{ $onlineCourse->name }}</h4>
                        <div>
                            <a href="{{ route('web.online-courses.edit', ['online_course' => $onlineCourse->id]) }}"
                                class="btn btn-sm btn-primary">Edit</a>
                            <a href="{{ route('web.online-courses.index') }}" class="btn btn-sm btn-secondary">Back to
                                List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Course Information</h5>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Category</p>
                                <p class="font-medium">{{ $onlineCourse->category->name }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Description</p>
                                <p>{{ $onlineCourse->description }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Online Price</p>
                                <p class="font-medium">{{ number_format($onlineCourse->online_price, 2) }} KD</p>
                            </div>
                            @if ($onlineCourse->prerequisites)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Prerequisites</p>
                                    <ul class="list-disc pl-5">
                                        @foreach ($onlineCourse->prerequisites as $prerequisite)
                                            <li>{{ $prerequisite }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if ($onlineCourse->learning_outcomes)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Learning Outcomes</p>
                                    <ul class="list-disc pl-5">
                                        @foreach ($onlineCourse->learning_outcomes as $outcome)
                                            <li>{{ $outcome }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Trainers</h5>
                            @if ($onlineCourse->trainers->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach ($onlineCourse->trainers as $trainer)
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full"
                                                    src="{{ $trainer->profile_photo_url }}" alt="{{ $trainer->name }}">
                                            </div>
                                            <div>
                                                <p class="font-medium">{{ $trainer->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $trainer->email }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No trainers assigned to this online course yet.</p>
                            @endif

                            <h5 class="font-medium text-gray-700 dark:text-gray-200 mt-6 mb-2">Sessions</h5>
                            @if ($onlineCourse->sessions->isNotEmpty())
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Date</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Start Time</th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    End Time</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($onlineCourse->sessions as $session)
                                                <tr>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        {{ $session->date->format('M d, Y') }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $session->start_time->format('h:i A') }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        {{ $session->end_time->format('h:i A') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No sessions scheduled for this online course yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection