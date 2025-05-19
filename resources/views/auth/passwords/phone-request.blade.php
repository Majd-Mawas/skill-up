<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => 'Reset Password'])

    @include('layouts.shared/head-css')
</head>

<body>

    <div class="bg-gradient-to-r from-rose-100 to-teal-100 dark:from-gray-700 dark:via-gray-900 dark:to-black">
        <div class="h-screen w-screen flex justify-center items-center">
            <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <div class="p-6">
                        <a href="{{ route('any', 'index') }}" class="block mb-8">
                            <img class="h-6 block dark:hidden" src="/images/logo-dark.png" alt="">
                            <img class="h-6 hidden dark:block" src="/images/logo-light.png" alt="">
                        </a>

                        @if (session('status'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-4" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.phone.send') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                    for="phone_number">Phone Number</label>
                                <input id="phone_number" type="tel"
                                    class="form-input @error('phone_number') border-danger @enderror"
                                    name="phone_number" value="{{ old('phone_number') }}"
                                    placeholder="Enter your phone number" required>
                                @error('phone_number')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-center mb-6">
                                <button type="submit" class="btn w-full text-white bg-primary">
                                    Send Reset Code
                                </button>
                            </div>
                        </form>

                        <p class="text-gray-500 dark:text-gray-400 text-center">
                            Remember your password? <a href="{{ route('login') }}" class="text-primary ms-1"><b>Log
                                    In</b></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
