<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Social Login Buttons -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 mb-4">
            {{ __('Or log in with') }}
        </p>

        <a href="{{ route('login.google') }}" class="inline-block mx-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/43/Google_2015_logo.svg" alt="Google" class="w-8 h-8 rounded-full" />
        </a>

        {{-- <a href="{{ route('login.github') }}" class="inline-block mx-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Octicons-mark-github.svg" alt="GitHub" class="w-8 h-8 rounded-full" />
        </a>

        <a href="{{ route('login.facebook') }}" class="inline-block mx-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook" class="w-8 h-8 rounded-full" />
        </a> --}}
    </div>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 inline">
            {{ __('Don\'t have an account?') }}
        </p>
        <a href="{{ route('register') }}" class="text-sm text-gray-700 underline hover:text-indigo-800 font-medium ml-1">
            {{ __('Create New Account') }}
        </a>
    </div>
</x-guest-layout>


