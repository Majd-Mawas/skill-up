@extends('layouts.vertical', ['title' => 'Interests', 'sub_title' => 'Details', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h4 class="card-title">Interest Details</h4>
                    <div class="flex gap-2">
                        <a href="{{ route('web.interests.edit', $interest) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('web.interests.destroy', $interest) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this interest?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-medium text-gray-700 dark:text-gray-200">Name</h5>
                            <p>{{ $interest->name }}</p>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-700 dark:text-gray-200">Status</h5>
                            <span
                                class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs font-medium {{ $interest->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $interest->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <h5 class="font-medium text-gray-700 dark:text-gray-200">Description</h5>
                            <p>{{ $interest->description }}</p>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <h5 class="font-medium text-gray-700 dark:text-gray-200">Categories</h5>
                            @if ($interest->categories->count() > 0)
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach ($interest->categories as $category)
                                        <span class="inline-flex items-center gap-1 py-1 px-2 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p>No categories assigned</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Users with this Interest</h4>
                </div>
                <div class="card-body">
                    @if ($interest->users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($interest->users as $user)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $user->name }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $user->email }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                {{ $user->roles->pluck('name')->implode(', ') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center py-4">No users have selected this interest.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection