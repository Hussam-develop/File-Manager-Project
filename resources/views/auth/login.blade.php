<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <a href="{{ route('selectGuard') }}">
        <x-primary-button class="ms">
            <- Go back </x-primary-button>
    </a>
    <div class="flex items-center justify-center mt-4">
        @if($type=='admin')
        <img alt="user-img" width="120px;" src="{{URL::asset('assets/images/admin.jpg')}}">
        @else
        <img alt="user-img" width="120px;" src="{{URL::asset('assets/images/User.jpg')}}">
        @endif
    </div>
    <form method="POST" action="{{ route('login.store') }}"
        class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 transition-transform transform hover:scale-105 duration-300">
        @csrf

        <!-- Email Address -->
        <input type="hidden" name="type" value="{{$type}}" />
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                class="block mt-1 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                type="password" name="password" required autocomplete="current-password" placeholder="your password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
