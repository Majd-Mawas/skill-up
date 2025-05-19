<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => 'Login'])

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
                            {{-- <div class="text-black text-xl text-">Skill Up</div> --}}
                        </a>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                    for="phone">Phone Number</label>
                                <input id="phone" class="form-input" type="tel"
                                    placeholder="Enter your phone number" name="phone" required>
                                @error('phone')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                    for="loggingPassword">Password</label>
                                <input id="loggingPassword" class="form-input" type="password"
                                    placeholder="Enter your password" name="password" required>
                                @error('password')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <input id="remember" type="checkbox" class="form-checkbox rounded" name="remember">
                                    <label for="remember" class="ms-2 text-sm text-gray-600 dark:text-gray-200">Remember
                                        me</label>
                                </div>
                                <a href="{{ route('password.phone.request') }}" class="text-sm text-primary">Forgot your
                                    password?</a>
                            </div>

                            <div class="flex justify-center mb-6">
                                <button class="btn w-full text-white bg-primary">Log In</button>
                            </div>
                        </form>

                        {{-- <div class="flex items-center my-6">
                            <div class="flex-auto mt-px border-t border-dashed border-gray-200 dark:border-slate-700">
                            </div>
                            <div class="mx-4 text-secondary">Or</div>
                            <div class="flex-auto mt-px border-t border-dashed border-gray-200 dark:border-slate-700">
                            </div>
                        </div>

                        <div class="flex gap-4 justify-center mb-6">
                            <a href="javascript:void(0)" class="btn border-light text-gray-400 dark:border-slate-700">
                                <span class="flex justify-center items-center gap-2">
                                    <i class="mgc_github_line text-info text-xl"></i>
                                    <span class="lg:block hidden">Github</span>
                                </span>
                            </a>
                            <a href="javascript:void(0)" class="btn border-light text-gray-400 dark:border-slate-700">
                                <span class="flex justify-center items-center gap-2">
                                    <i class="mgc_google_line text-danger text-xl"></i>
                                    <span class="lg:block hidden">Google</span>
                                </span>
                            </a>
                            <a href="javascript:void(0)" class="btn border-light text-gray-400 dark:border-slate-700">
                                <span class="flex justify-center items-center gap-2">
                                    <i class="mgc_facebook_line text-primary text-xl"></i>
                                    <span class="lg:block hidden">Facebook</span>
                                </span>
                            </a>
                        </div> --}}

                        {{-- <p class="text-gray-500 dark:text-gray-400 text-center">
                            Forgot your password? <a href="{{ route('password.phone.request') }}"
                                class="text-primary ms-1"><b>Reset it</b></a>
                        </p> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>
