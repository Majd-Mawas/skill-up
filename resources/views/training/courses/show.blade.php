@extends('layouts.vertical', ['title' => 'Courses', 'sub_title' => 'Show', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Course Details') }}
                        </p>
                        <div class="flex space-x-2">
                            <a href="{{ route('web.courses.edit', $course) }}" class="btn bg-primary text-white">
                                {{ __('Edit Course') }}
                            </a>
                            <a href="{{ route('web.courses.index') }}" class="btn bg-secondary text-white">
                                {{ __('Back to List') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Name') }}
                            </label>
                            <p class="text-gray-600">{{ $course->name }}</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Category') }}
                            </label>
                            <p class="text-gray-600">{{ $course->category->name }}</p>
                        </div>

                        <!-- Description -->
                        <div class="lg:col-span-2">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                {{ __('Description') }}
                            </label>
                            <p class="text-gray-600">{{ $course->description }}</p>
                        </div>
                    </div>

                    <!-- Course Levels Section -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">{{ __('Course Levels') }}</h2>
                            <a href="{{ route('web.courses.levels.create', $course) }}" class="btn bg-primary text-white">
                                {{ __('Add New Level') }}
                            </a>
                        </div>

                        @if ($course->levels->isNotEmpty())
                            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Order') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Name') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Description') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Status') }}
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="sortable-levels">
                                        @foreach ($course->levels as $level)
                                            <tr data-id="{{ $level->id }}" class="cursor-move">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $level->level_order }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $level->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-500">
                                                        {{ Str::limit($level->description, 100) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $level->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $level->is_active ? __('Active') : __('Inactive') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-3">
                                                        <a href="{{ route('web.courses.levels.edit', [$course, $level]) }}"
                                                            class="text-indigo-600 hover:text-indigo-900">
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form
                                                            action="{{ route('web.courses.levels.destroy', [$course, $level]) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                onclick="return confirm('{{ __('Are you sure you want to delete this level?') }}')">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                {{ __('No levels found.') }}
                                <a href="{{ route('web.courses.levels.create', $course) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    {{ __('Add one now') }}
                                </a>
                            </div>
                        @endif
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
                                        row.querySelector('td:first-child').textContent =
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
