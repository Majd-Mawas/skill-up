@extends('layouts.vertical', ['title' => 'Online Courses', 'sub_title' => 'All', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
                                <form method="GET" action="{{ route('web.online-courses.index') }}">
                                    <div class="relative">
                                        <label for="table-search" class="sr-only">Search</label>
                                        <input type="text" name="search" id="table-search"
                                            value="{{ request('search') }}" class="form-input ps-11"
                                            placeholder="Search for items">
                                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
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
                                <a href="{{ route('web.online-courses.create') }}"
                                    class="btn border-primary text-primary hover:bg-primary hover:text-white">
                                    Create Online Course
                                </a>
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
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Name') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Category') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Trainers') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Price') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Action') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td class="py-3 ps-4">
                                                <div class="flex items-center h-5">
                                                    <input id="table-pagination-checkbox-{{ $course->id }}"
                                                        type="checkbox" class="form-checkbox rounded">
                                                    <label for="table-pagination-checkbox-{{ $course->id }}"
                                                        class="sr-only">Checkbox</label>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $course->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $course->category->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                                {{ $course->trainers->count() }} trainer(s)
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                                @if ($course->trainers->isNotEmpty())
                                                    {{ number_format($course->online_price, 2) }} SP
                                                @else
                                                    Not set
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <a class="text-primary hover:text-sky-700 mx-2"
                                                    href="{{ route('web.online-courses.show', ['online_course' => $course->id]) }}">
                                                    Show
                                                </a>
                                                <a class="text-primary hover:text-sky-700 mx-2"
                                                    href="{{ route('web.online-courses.edit', ['online_course' => $course->id]) }}">
                                                    Edit
                                                </a>
                                                <form
                                                    action="{{ route('web.online-courses.destroy', ['online_course' => $course->id]) }}"
                                                    method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 mx-2 delete-btn"
                                                        data-name="{{ $course->name }}">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-4">
                            {{ $courses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const name = this.getAttribute('data-name');
                    if (confirm(`Are you sure you want to delete the online course "${name}"?`)) {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
@endsection
