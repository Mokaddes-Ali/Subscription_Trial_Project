<x-guest-layout>
    <div class="max-w-md mx-auto mt-8 bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-semibold text-center text-gray-900 mb-4">{{ __('Forgot your password?') }}</h2>

        <div class="mb-6 text-lg text-gray-600 text-center">
            {{ __('No problem. Just let us know your email address and we will send you a reset link.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input
                    id="email"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-center space-x-4 mt-4">
                <!-- Reset Link Button -->
                <x-primary-button class="w-[160px] py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    {{ __('Reset Link') }}
                </x-primary-button>
            </div>
        </form>

        <div class="flex justify-center mt-6">
            <!-- Login Button -->
            <x-primary-button  class="w-[160px] py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <a href="{{ route('login') }}" >{{ __('Back to Login') }}</a>
            </x-primary-button>
        </div>
    </div>
</x-guest-layout>
