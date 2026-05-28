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
                <input id="remember_me" type="checkbox" class="rounded border-cyber-purple/30 bg-cyber-dark/40 text-cyber-cyan shadow-sm focus:ring-cyber-cyan/50" name="remember">
                <span class="ms-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-400 hover:text-cyber-cyan rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyber-cyan/50 transition-all duration-200" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-gradient-to-r from-cyber-purple to-cyber-cyan hover:shadow-neon-cyan border-transparent transition-all duration-200">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Registration Link -->
        <div class="mt-4 text-center">
            <span class="text-sm text-gray-400">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="ms-1 text-sm font-semibold text-cyber-cyan hover:text-cyber-purple underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyber-cyan/50 rounded-md transition-all duration-200">
                Daftar Sekarang
            </a>
        </div>
    </form>
</x-guest-layout>
