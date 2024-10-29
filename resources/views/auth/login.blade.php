<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-gray-900">
                            Welcome back
                        </h2>
                        <p class="mt-2 text-sm text-gray-600">
                            Please sign in to your account
                        </p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="rounded-md shadow-sm -space-y-px">
                            <div>
                                <x-input-label for="email" :value="__('Email')" class="sr-only" />
                                <x-text-input id="email" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email address" />
                            </div>
                            <div>
                                <x-input-label for="password" :value="__('Password')" class="sr-only" />
                                <x-text-input id="password" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" type="password" name="password" required autocomplete="current-password" placeholder="Password" />
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                    {{ __('Remember me') }}
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-primary-button class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="text-center">
                        <a href="{{ route('google-auth') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Continue with Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>