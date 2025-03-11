<x-guest-layout>
    <div class="mb-4 text-md text-green-600">
        {{ __('Success the 6-digit code sent to your email.') }}
    </div>

    <form method="POST" action="{{ route('verify.otp') }}" class="mt-4">
        @csrf

        <div>
            <label for="verification_code" class="block text-sm font-medium text-gray-700">
                {{ __('Verification Code') }}
            </label>
            <input type="number" id="verification_code" name="two_factor_code" maxlength="6" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mt-4 flex items-center justify-between">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{ __('Verify') }}
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('verify.resend') }}" >
        @csrf
        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none">
            {{ __('Resend Code') }}
        </button>
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('Log Out') }}
        </button>
    </form>
</x-guest-layout>
