@extends('layouts.vertical', ['title' => 'Courses', 'sub_title' => 'All', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <style>
        div.px-4.py-4>nav>div {
            align-items: center;
            gap: 10px;
        }
    </style>
    <div class="grid grid-cols-12">
        <div class="col-span-12">

            <div class="overflow-x-auto">
                <div class="min-w-full inline-block align-middle">
                    <div class="border rounded-lg divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
                        <div class="py-3 px-4 flex flex-wrap justify-between">
                            <div>

                                <form method="GET" action="{{ route('courses.index') }}">
                                    <div class="relative max-w-xs">
                                        <label for="table-search" class="sr-only">Search</label>
                                        <input type="text" name="search" id="table-search"
                                            value="{{ request('search') }}" class="form-input ps-11"
                                            placeholder="Search for items">
                                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                            <!-- Icon -->
                                            <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <a href="{{ route('courses.create') }}"
                                    class="btn border-primary text-primary hover:bg-primary hover:text-white">Create
                                    Course</a>

                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="py-3 px-4 pe-0">
                                            <div class="flex items-center h-5">
                                                <input id="table-pagination-checkbox-all" type="checkbox"
                                                    class="form-checkbox rounded">
                                                <label for="table-pagination-checkbox-all" class="sr-only">Checkbox</label>
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration
                                            Hrs</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td class="py-3 ps-4">
                                                <div class="flex items-center h-5">
                                                    <input id="table-pagination-checkbox-1" type="checkbox"
                                                        class="form-checkbox rounded">
                                                    <label for="table-pagination-checkbox-1"
                                                        class="sr-only">Checkbox</label>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $course->title }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $course->category->name }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $course->duration_hours }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $course->price . '$' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <a class="text-primary hover:text-sky-700 mx-2"
                                                    href="{{ route('courses.edit', ['course' => $course->id]) }}">Edit</a>
                                                <form action="{{ route('courses.destroy', ['course' => $course->id]) }}"
                                                    method="POST" style="display: inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-primary hover:text-red-700 bg-transparent border-none p-0 m-0 cursor-pointer">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div>
                            <div class="py-4 px-4">
                                {{ $courses->appends(request()->query())->links() }}
                            </div>
                        </div>
                        {{-- <div class="py-1 px-4">
                            <nav class="flex items-center space-x-2">
                                <a class="text-gray-400 hover:text-primary p-4 inline-flex items-center gap-2 font-medium rounded-md"
                                    href="#">
                                    <span aria-hidden="true">«</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="w-10 h-10 bg-primary text-white p-4 inline-flex items-center text-sm font-medium rounded-full"
                                    href="#" aria-current="page">1</a>
                                <a class="w-10 h-10 text-gray-400 hover:text-primary p-4 inline-flex items-center text-sm font-medium rounded-full"
                                    href="#">2</a>
                                <a class="w-10 h-10 text-gray-400 hover:text-primary p-4 inline-flex items-center text-sm font-medium rounded-full"
                                    href="#">3</a>
                                <a class="text-gray-400 hover:text-primary p-4 inline-flex items-center gap-2 font-medium rounded-md"
                                    href="#">
                                    <span class="sr-only">Next</span>
                                    <span aria-hidden="true">»</span>
                                </a>
                            </nav>
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
