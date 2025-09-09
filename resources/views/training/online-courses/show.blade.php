@extends('layouts.vertical', ['title' => 'Online Course Details', 'sub_title' => 'View', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-10 lg:col-start-2">
            <div class="card overflow-hidden transition-all duration-300 hover:shadow-lg">
                <div class="card-header bg-primary/5 border-b border-primary/10">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h4 class="card-title text-xl font-bold text-primary mb-1">{{ $onlineCourse->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Course ID: #{{ $onlineCourse->id }}</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('web.online-courses.edit', ['online_course' => $onlineCourse->id]) }}"
                                class="btn btn-sm btn-primary flex items-center gap-2 px-4 py-2 shadow-sm hover:shadow transition-all">
                                <i class="ti ti-edit text-lg"></i>
                                <span>Edit Course</span>
                            </a>
                            <a href="{{ route('web.online-courses.index') }}"
                                class="btn btn-sm btn-secondary flex items-center gap-2 px-4 py-2 shadow-sm hover:shadow transition-all">
                                <i class="ti ti-arrow-left text-lg"></i>
                                <span>Back to List</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-6">
                    <div class="mb-6">
                        <h5
                            class="font-semibold text-base border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 text-primary/80">
                            Course Information</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                class="bg-gray-50 dark:bg-gray-800/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                                <h6 class="font-medium mb-2 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <i class="ti ti-category text-primary"></i>
                                    <span>Category</span>
                                </h6>
                                <div class="text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $onlineCourse->category->name }}
                                </div>
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-gray-800/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                                <h6 class="font-medium mb-2 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <i class="ti ti-currency-dollar text-primary"></i>
                                    <span>Online Price</span>
                                </h6>
                                <div class="text-gray-800 dark:text-gray-200 font-medium text-lg">
                                    <span class="text-primary font-bold">SP</span>
                                    {{ number_format($onlineCourse->online_price, 2) }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-6 bg-gray-50 dark:bg-gray-800/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h6 class="font-medium mb-2 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i class="ti ti-file-description text-primary"></i>
                                <span>Description</span>
                            </h6>
                            <div class="text-gray-800 dark:text-gray-200 prose max-w-none dark:prose-invert prose-sm">
                                {{ $onlineCourse->description }}
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        @if ($onlineCourse->prerequisites)
                            <div
                                class="bg-gray-50 dark:bg-gray-800/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 hover:border-primary/20">
                                <h6 class="font-medium mb-3 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <i class="ti ti-list-check text-primary"></i>
                                    <span>Prerequisites</span>
                                </h6>
                                <ul class="list-disc pl-5 space-y-2 text-gray-800 dark:text-gray-200">
                                    @if (is_string($onlineCourse->prerequisites))
                                        @foreach (explode("\n", $onlineCourse->prerequisites) as $prerequisite)
                                            @if (trim($prerequisite))
                                                <li class="text-gray-700 dark:text-gray-300">{{ trim($prerequisite) }}</li>
                                            @endif
                                        @endforeach
                                    @elseif(is_array($onlineCourse->prerequisites))
                                        @foreach ($onlineCourse->prerequisites as $prerequisite)
                                            @if (trim($prerequisite))
                                                <li class="text-gray-700 dark:text-gray-300">{{ trim($prerequisite) }}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif

                        @if ($onlineCourse->learning_outcomes)
                            <div
                                class="bg-gray-50 dark:bg-gray-800/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 hover:border-primary/20">
                                <h6 class="font-medium mb-3 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <i class="ti ti-target text-primary"></i>
                                    <span>Learning Outcomes</span>
                                </h6>
                                <ul class="list-disc pl-5 space-y-2 text-gray-800 dark:text-gray-200">
                                    @if (is_string($onlineCourse->learning_outcomes))
                                        @foreach (explode("\n", $onlineCourse->learning_outcomes) as $outcome)
                                            @if (trim($outcome))
                                                <li class="text-gray-700 dark:text-gray-300">{{ trim($outcome) }}</li>
                                            @endif
                                        @endforeach
                                    @elseif(is_array($onlineCourse->learning_outcomes))
                                        @foreach ($onlineCourse->learning_outcomes as $outcome)
                                            @if (trim($outcome))
                                                <li class="text-gray-700 dark:text-gray-300">{{ trim($outcome) }}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Course Image Section -->
                    @if ($onlineCourse->image_url)
                        <div class="mt-6">
                            <div
                                class="bg-gray-50 dark:bg-gray-800/30 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                                <h6 class="font-medium mb-3 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <i class="ti ti-photo text-primary"></i>
                                    <span>Course Image</span>
                                </h6>
                                <div class="flex justify-center">
                                    <img src="{{ $onlineCourse->image_url }}" alt="{{ $onlineCourse->name }}"
                                        class="rounded-lg border-2 border-primary/20 shadow-md max-h-96 object-contain">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-100 dark:border-gray-800 p-5">
                    <h5
                        class="font-semibold text-primary/80 mb-4 text-base border-b border-gray-200 dark:border-gray-700 pb-2 flex items-center gap-2">
                        <i class="ti ti-users text-lg"></i>
                        <span>Course Trainers</span>
                    </h5>
                    @if ($onlineCourse->trainers->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($onlineCourse->trainers as $trainer)
                                <div
                                    class="flex items-start space-x-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all duration-300 hover:border-primary/20">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $trainer->profile_photo_url }}" alt="{{ $trainer->name }}"
                                            class="h-16 w-16 rounded-full object-cover border-2 border-primary/20">
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-gray-200 text-base">
                                            {{ $trainer->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                                            <i class="ti ti-mail text-xs"></i>
                                            <span>{{ $trainer->email }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/30 border border-gray-200 dark:border-gray-700 text-center">
                            <i class="ti ti-user-off text-3xl text-gray-400 dark:text-gray-600 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400 italic">No trainers have been assigned to this course
                                yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
