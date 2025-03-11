<x-guest-layout>
    <div class="mb-4 text-md text-green-600 font-semibold text-center">
        {{ __('Success! A 6-digit verification code has been sent to your email.') }}
    </div>

    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <form method="POST" action="{{ route('verify.otp') }}" class="space-y-4">
            @csrf
            <div>
                <label for="verification_code" class="block text-sm font-medium text-gray-700">
                    {{ __('Verification Code') }}
                </label>
                <input type="number" id="verification_code" name="two_factor_code" maxlength="6" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{ __('Verify') }}
            </button>
        </form>

        <div class="flex justify-between items-center mt-4">
            <form method="POST" action="{{ route('verify.resend') }}">
                @csrf
                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    {{ __('Resend Code') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
