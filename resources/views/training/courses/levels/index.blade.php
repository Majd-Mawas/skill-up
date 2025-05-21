@extends('layouts.vertical', ['title' => 'Course Levels', 'sub_title' => 'List', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

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
                                <form method="GET" action="{{ route('web.courses.levels.index', $course) }}">
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
                                <a href="{{ route('web.courses.levels.create', $course) }}"
                                    class="btn border-primary text-primary hover:bg-primary hover:text-white">
                                    {{ __('Add New Level') }}
                                </a>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                                role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

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
                                            {{ __('Order') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Name') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Description') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="sortable-levels">
                                    @forelse ($levels as $level)
                                        <tr data-id="{{ $level->id }}" class="cursor-move">
                                            <td class="py-3 ps-4">
                                                <div class="flex items-center h-5">
                                                    <input id="table-pagination-checkbox-1" type="checkbox"
                                                        class="form-checkbox rounded">
                                                    <label for="table-pagination-checkbox-1"
                                                        class="sr-only">Checkbox</label>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $level->level_order }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $level->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                                {{ Str::limit($level->description, 100) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $level->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $level->is_active ? __('Active') : __('Inactive') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <a class="text-primary hover:text-sky-700 mx-2"
                                                    href="{{ route('web.courses.levels.edit', [$course, $level]) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('web.courses.levels.destroy', [$course, $level]) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this level?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-primary hover:text-red-700 bg-transparent border-none p-0 m-0 cursor-pointer">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                {{ __('No levels found.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <div class="py-4 px-4">
                                {{ $levels->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sortable = new Sortable(document.getElementById('sortable-levels'), {
                    animation: 150,
                    onEnd: function(evt) {
                        const levels = Array.from(evt.to.children).map((row, index) => ({
                            id: row.dataset.id,
                            order: index + 1
                        }));

                        fetch('{{ route('web.courses.levels.reorder', $course) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    levels
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    // Update the order numbers in the table
                                    Array.from(evt.to.children).forEach((row, index) => {
                                        row.querySelector('td:nth-child(2)').textContent =
                                            index + 1;
                                    });
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            });
        </script>
    @endpush
@endsection
