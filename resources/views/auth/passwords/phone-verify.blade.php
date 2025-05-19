<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => 'Verify Reset Code'])

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

                        @if (session('error'))
                            <div class="alert alert-danger mb-4" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.phone.verify') }}">
                            @csrf
                            <input type="hidden" name="phone_number" value="{{ session('phone_number') }}">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                    for="code">Verification Code</label>
                                <input id="code" type="text"
                                    class="form-input @error('code') border-danger @enderror" name="code"
                                    placeholder="Enter verification code" required>
                                @error('code')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-center mb-6">
                                <button type="submit" class="btn w-full text-white bg-primary">
                                    Verify Code
                                </button>
                            </div>
                        </form>

                        <p class="text-gray-500 dark:text-gray-400 text-center">
                            Didn't receive the code? <a href="{{ route('password.phone.request') }}"
                                class="text-primary ms-1"><b>Request Again</b></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
