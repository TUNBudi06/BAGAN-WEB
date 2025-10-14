<?php

use function Livewire\Volt\{state, rules};
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

state(['username' => '', 'password' => '', 'remember' => false]);

rules(['username' => 'required|string', 'password' => 'required|string']);

$login = function () {
    $this->validate();

    if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember)) {
        request()->session()->regenerate();
        return redirect()->intended(route('base'));
    }

    throw ValidationException::withMessages([
        'username' => __('The provided credentials do not match our records.'),
    ]);
};

?>
<form wire:submit="login" class="mt-8 space-y-6">
    <div class="rounded-md shadow-sm space-y-4">
        {{-- Username Field --}}
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Username
            </label>
            <input
                wire:model="username"
                id="username"
                name="username"
                type="text"
                autocomplete="username"
                required
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 sm:text-sm"
                placeholder="Enter your username"
            >
            @error('username')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Field --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Password
            </label>
            <input
                wire:model="password"
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 sm:text-sm"
                placeholder="Enter your password"
            >
            @error('password')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Remember Me and Forgot Password --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input
                wire:model="remember"
                id="remember"
                name="remember"
                type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                Remember me
            </label>
        </div>

        @if (Route::has('password.request'))
            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                    Forgot your password?
                </a>
            </div>
        @endif
    </div>

    {{-- Submit Button --}}
    <div>
        <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
        >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
            Sign in
        </button>
    </div>

    {{-- Register Link --}}
    @if (Route::has('register'))
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                    Register here
                </a>
            </p>
        </div>
    @endif
</form>
