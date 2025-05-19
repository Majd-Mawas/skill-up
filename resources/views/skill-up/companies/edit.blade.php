@extends('layouts.vertical', ['title' => 'Companies', 'sub_title' => 'Update', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">

            <form action="{{ route('companies.update', ['company' => $company->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('Update Company') }}
                        </p>
                    </div>
                    <div class="p-6">

                        <div class="grid gap-6">
                            <!-- Title -->
                            <div>
                                <label for="name"
                                    class="text-gray-800 text-sm font-medium inline-block mb-2">Name</label>
                                <input type="text" id="name" name="name" class="form-input"
                                    value="{{ old('name', $company->name) }}">
                                @error('name')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Title -->
                            <div>
                                <label for="contact_email" class="text-gray-800 text-sm font-medium inline-block mb-2">Contact
                                    Email</label>
                                <input type="text" id="contact_email" name="contact_email" class="form-input"
                                    value="{{ old('contact_email', $company->contact_email) }}">
                                @error('contact_email')
                                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn bg-primary text-white my-2 ms-auto">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
