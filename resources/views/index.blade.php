@extends('layouts.vertical', ['title' => 'Dashboard', 'sub_title' => 'Menu', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid 2xl:grid-cols-4 gap-6 mb-6">

        <div class="2xl:col-span-3">
            <div class="grid grid-cols-12 gap-6 mb-6">
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="flex items-center justify-center h-9 w-9 rounded-full bg-primary-500 bg-opacity-20">
                                        <i class="fas fa-building text-primary-500 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-zinc-300 card-title">Training
                                        Centers</h5>
                                    <span class="text-gray-900 dark:text-white text-2xl font-bold task-count">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        {{-- <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">View All</a></li>
                                            <li><a class="dropdown-item" href="#">Details</a></li>
                                        </ul> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="flex items-center justify-center h-9 w-9 rounded-full bg-orange-500 bg-opacity-20">
                                        <i class="fas fa-users text-orange-500 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-zinc-300 card-title">Trainers
                                    </h5>
                                    <span class="text-gray-900 dark:text-white text-2xl font-bold task-count">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        {{-- <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">View All</a></li>
                                            <li><a class="dropdown-item" href="#">Details</a></li>
                                        </ul> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="flex items-center justify-center h-9 w-9 rounded-full bg-red-500 bg-opacity-20">
                                        <i class="fas fa-book-open text-red-500 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-zinc-300 card-title">Courses</h5>
                                    <span class="text-gray-900 dark:text-white text-2xl font-bold task-count">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        {{-- <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">View All</a></li>
                                            <li><a class="dropdown-item" href="#">Details</a></li>
                                        </ul> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="flex items-center justify-center h-9 w-9 rounded-full bg-green-500 bg-opacity-20">
                                        <i class="fas fa-user-graduate text-green-500 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-zinc-300 card-title">Students
                                    </h5>
                                    <span class="text-gray-900 dark:text-white text-2xl font-bold task-count">0</span>
                                </div>
                                <div class="ml-auto">
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        {{-- <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">View All</a></li>
                                            <li><a class="dropdown-item" href="#">Details</a></li>
                                        </ul> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
                <div class="card">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-base mb-1 text-gray-600 dark:text-gray-400">Project Dashboard</h4>
                                <p class="font-normal text-sm text-gray-400 truncate dark:text-gray-500">New Task Assign
                                </p>
                            </div>

                            <div>
                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                    data-fc-placement="left-start" type="button">
                                    <i class="mgc_more_1_fill text-xl"></i>
                                </button>

                                <div
                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_add_circle_line"></i> Add
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_edit_line"></i> Edit
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                        href="javascript:void(0)">
                                        <i class="mgc_copy_2_line"></i> Copy
                                    </a>
                                    <div class="h-px bg-gray-200 dark:bg-gray-700 my-2 -mx-2"></div>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-danger hover:bg-danger/5"
                                        href="javascript:void(0)">
                                        <i class="mgc_delete_line"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-end">
                            <div class="flex-grow">
                                <p class="text-[13px] text-gray-400 dark:text-gray-500 font-semibold"><i
                                        class="mgc_alarm_2_line"></i> 4 Hrs ago</p>
                            </div>
                            <div class="flex">
                                <a href="javascript:void(0);">
                                    <img src="/images/users/avatar-1.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                                <a href="javascript:void(0);" class="-ms-2">
                                    <img src="/images/users/avatar-2.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-base mb-1 text-gray-600 dark:text-gray-400">Admin Template</h4>
                                <p class="font-normal text-sm text-gray-400 truncate dark:text-gray-500">New Task
                                    Assign</p>
                            </div>
                            <div>
                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                    data-fc-placement="left-start" type="button">
                                    <i class="mgc_more_1_fill text-xl"></i>
                                </button>

                                <div
                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_add_circle_line"></i> Add
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_edit_line"></i> Edit
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                        href="javascript:void(0)">
                                        <i class="mgc_copy_2_line"></i> Copy
                                    </a>
                                    <div class="h-px bg-gray-200 dark:bg-gray-700 my-2 -mx-2"></div>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-danger hover:bg-danger/5"
                                        href="javascript:void(0)">
                                        <i class="mgc_delete_line"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-end">
                            <div class="flex-grow">
                                <p class="text-[13px] text-gray-400 dark:text-gray-500 font-semibold"><i
                                        class="mgc_alarm_2_line"></i> 3 Hrs ago</p>
                            </div>
                            <div class="flex">
                                <a href="javascript:void(0);">
                                    <img src="/images/users/avatar-3.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                                <a href="javascript:void(0);" class="-ms-2">
                                    <img src="/images/users/avatar-4.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-base mb-1 text-gray-600 dark:text-gray-400">Client Project</h4>
                                <p class="font-normal text-sm text-gray-400 truncate dark:text-gray-500">New Task
                                    Assign</p>
                            </div>
                            <div>
                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                    data-fc-placement="left-start" type="button">
                                    <i class="mgc_more_1_fill text-xl"></i>
                                </button>

                                <div
                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_add_circle_line"></i> Add
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_edit_line"></i> Edit
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                        href="javascript:void(0)">
                                        <i class="mgc_copy_2_line"></i> Copy
                                    </a>
                                    <div class="h-px bg-gray-200 dark:bg-gray-700 my-2 -mx-2"></div>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-danger hover:bg-danger/5"
                                        href="javascript:void(0)">
                                        <i class="mgc_delete_line"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-end">
                            <div class="flex-grow">
                                <p class="text-[13px] text-gray-400 dark:text-gray-500 font-semibold"><i
                                        class="mgc_alarm_2_line"></i> 5 Hrs ago</p>
                            </div>
                            <div class="flex">
                                <a href="javascript:void(0);">
                                    <img src="/images/users/avatar-5.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                                <a href="javascript:void(0);" class="-ms-2">
                                    <img src="/images/users/avatar-6.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-base mb-1 text-gray-600 dark:text-gray-400">Figma Design</h4>
                                <p class="font-normal text-sm text-gray-400 truncate dark:text-gray-500">New Task
                                    Assign
                                </p>
                            </div>
                            <div>
                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                    data-fc-placement="left-start" type="button">
                                    <i class="mgc_more_1_fill text-xl"></i>
                                </button>

                                <div
                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_add_circle_line"></i> Add
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        <i class="mgc_edit_line"></i> Edit
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                        href="javascript:void(0)">
                                        <i class="mgc_copy_2_line"></i> Copy
                                    </a>
                                    <div class="h-px bg-gray-200 dark:bg-gray-700 my-2 -mx-2"></div>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-danger hover:bg-danger/5"
                                        href="javascript:void(0)">
                                        <i class="mgc_delete_line"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-end">
                            <div class="flex-grow">
                                <p class="text-[13px] text-gray-400 dark:text-gray-500 font-semibold"><i
                                        class="mgc_alarm_2_line"></i> 1 Day ago</p>
                            </div>
                            <div class="flex">
                                <a href="javascript:void(0);">
                                    <img src="/images/users/avatar-7.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                                <a href="javascript:void(0);" class="-ms-2">
                                    <img src="/images/users/avatar-8.jpg"
                                        class="rounded-full h-8 w-8 border-2 border-gray-300 dark:border-gray-700"
                                        alt="friend">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="col-span-1 flex">
                    <div class="card w-full">
                        <div class="p-6 h-full flex flex-col">
                            <h4 class="card-title">Bookings</h4>

                            <div id="bookings-type-chart" class="apex-charts my-8 flex-grow" data-colors="#0acf97,#3073F1">
                            </div>
                            {{--
                            <div class="flex justify-center">
                                <div class="w-1/2 text-center">
                                    <h5>Active</h5>
                                    <p class="fw-semibold text-muted">
                                        <i class="mgc_round_fill text-primary"></i> Students
                                    </p>
                                </div>
                                <div class="w-1/2 text-center">
                                    <h5>Completed</h5>
                                    <p class="fw-semibold text-muted">
                                        <i class="mgc_round_fill text-success"></i> Students
                                    </p>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 flex">
                    <div class="card w-full">
                        <div class="p-6 h-full flex flex-col">
                            <div class="flex justify-between items-center">
                                <h4 class="card-title">Training Center Statistics</h4>
                            </div>

                            <div dir="ltr" class="mt-2 flex-grow">
                                <div id="training-centers-chart" class="apex-charts h-full" data-colors="#cbdcfc,#3073F1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- Grid End -->


        <!-- Grid End -->

        <div class="grid 2xl:grid-cols-4 md:grid-cols-2 gap-6">
            <div class="2xl:col-span-2 md:col-span-2">
                <div class="card">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <h4 class="card-title">Most Booked Courses</h4>
                            <div>
                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                    data-fc-placement="left-start" type="button">
                                    <i class="mgc_more_2_fill text-xl"></i>
                                </button>

                                <div
                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        View All
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                        href="javascript:void(0)">
                                        Export Data
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                        href="javascript:void(0)">
                                        Last Week
                                    </a>
                                    <a class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                                        href="javascript:void(0)">
                                        Last Month
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-1 items-center gap-4">
                            {{-- <div class="md:order-1 order-2">
                                <div class="flex flex-col gap-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i
                                                class="mgc_round_fill h-10 w-10 flex justify-center items-center rounded-full bg-primary/25 text-lg text-primary"></i>
                                        </div>
                                        <div class="flex-grow ms-3">
                                            <h5 class="fw-semibold mb-1">Product Design</h5>
                                            <ul class="flex items-center gap-2">
                                                <li class="list-inline-item"><b>26</b> Total Projects</li>
                                                <li class="list-inline-item">
                                                    <div class="w-1 h-1 rounded bg-gray-400"></div>
                                                </li>
                                                <li class="list-inline-item"><b>4</b> Employees</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i
                                                class="mgc_round_fill h-10 w-10 flex justify-center items-center rounded-full bg-danger/25 text-lg text-danger"></i>
                                        </div>
                                        <div class="flex-grow ms-3">
                                            <h5 class="fw-semibold mb-1">Web Development</h5>
                                            <ul class="flex items-center gap-2">
                                                <li class="list-inline-item"><b>30</b> Total Projects</li>
                                                <li class="list-inline-item">
                                                    <div class="w-1 h-1 rounded bg-gray-400"></div>
                                                </li>
                                                <li class="list-inline-item"><b>5</b> Employees</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i
                                                class="mgc_round_fill h-10 w-10 flex justify-center items-center rounded-full bg-success/25 text-lg text-success"></i>
                                        </div>
                                        <div class="flex-grow ms-3">
                                            <h5 class="fw-semibold mb-1">Illustration Design</h5>
                                            <ul class="flex items-center gap-2">
                                                <li class="list-inline-item"><b>12</b> Total Projects</li>
                                                <li class="list-inline-item">
                                                    <div class="w-1 h-1 rounded bg-gray-400"></div>
                                                </li>
                                                <li class="list-inline-item"><b>3</b> Employees</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i
                                                class="mgc_round_fill h-10 w-10 flex justify-center items-center rounded-full bg-warning/25 text-lg text-warning"></i>
                                        </div>
                                        <div class="flex-grow ms-3">
                                            <h5 class="fw-semibold mb-1">UI/UX Design</h5>
                                            <ul class="flex items-center gap-2">
                                                <li class="list-inline-item"><b>8</b> Total Projects</li>
                                                <li class="list-inline-item">
                                                    <div class="w-1 h-1 rounded bg-gray-400"></div>
                                                </li>
                                                <li class="list-inline-item"><b>4</b> Employees</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="md:order-2 order-1">
                                <div id="courses-chart" class="apex-charts"
                                    data-colors="#3073F1,#ff679b,#0acf97,#ffbc00"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Grid End -->
    @endsection

    @section('script')
        @vite(['resources/js/pages/dashboard.js', 'resources/js/dashboard-stats.js'])
    @endsection
