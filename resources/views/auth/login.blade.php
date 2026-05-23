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

            <x-password-input id="password" class="block mt-1 w-full"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#63c1f7] shadow-sm focus:ring-[#63c1f7]" name="remember">
                <span class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#63c1f7]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-gradient-to-r from-[#63c1f7] to-[#5ca0d1] hover:from-[#56b1e6] hover:to-[#4aa0d1] border-transparent">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Registration Link -->
        <div class="mt-4 text-center">
            <span class="text-sm text-gray-600">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="ms-1 text-sm font-semibold text-[#63c1f7] hover:text-[#56b1e6] underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#63c1f7] rounded-md">
                Daftar Sekarang
            </a>
        </div>
    </form>
</x-guest-layout>
