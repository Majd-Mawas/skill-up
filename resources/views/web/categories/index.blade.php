@extends('layouts.vertical', ['title' => 'Categories', 'sub_title' => 'List', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
                                <form method="GET" action="{{ route('web.categories.index') }}">
                                    <div class="relative">
                                        <label for="table-search" class="sr-only">Search</label>
                                        <input type="text" name="search" id="table-search"
                                            value="{{ request('search') }}" class="form-input ps-11"
                                            placeholder="Search categories...">
                                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                                            <svg class="h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <a href="{{ route('web.categories.create') }}"
                                    class="btn border-primary text-primary hover:bg-primary hover:text-white">
                                    Add Category
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
                                            Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Description
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Courses
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Created At
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($categories as $category)
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
                                                {{ $category->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                                {{ Str::limit($category->description, 50) }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $category->courses_count }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $category->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <a href="{{ route('web.categories.edit', $category) }}"
                                                    class="text-primary hover:text-sky-700 mx-2">Edit</a>
                                                <form action="{{ route('web.categories.destroy', $category) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-primary hover:text-red-700 bg-transparent border-none p-0 m-0 cursor-pointer">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No categories found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <div class="py-4 px-4">
                                {{ $categories->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
