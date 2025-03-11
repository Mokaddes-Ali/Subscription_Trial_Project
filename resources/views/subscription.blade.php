<h1>Hello User</h1>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        {{ __('Log Out') }}
    </button>
</form>
