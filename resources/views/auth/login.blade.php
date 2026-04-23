<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-400">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-300 font-semibold" />
                <x-input id="email" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            </div>

            <div class="mt-5">
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-300 font-semibold" />
                <x-input id="password" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <div class="flex items-center justify-between mt-5">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" class="bg-white/5 border-white/10 text-[#4169E1] focus:ring-[#4169E1]" />
                    <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-[#4169E1] hover:text-blue-400 font-medium transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4169E1] focus:ring-offset-2 focus:ring-offset-gray-900">
                    {{ __('Sign In') }}
                </button>
            </div>

            @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-gray-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#4169E1] hover:text-blue-400 font-semibold transition-colors">Create one</a>
            </p>
            @endif
        </form>
    </x-authentication-card>
</x-guest-layout>
