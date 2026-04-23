<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-400">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-400">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-300 font-semibold" />
                <x-input id="email" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition-all duration-200">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>

            <p class="mt-6 text-center text-sm text-gray-500">
                Remember your password?
                <a href="{{ route('login') }}" class="text-[#4169E1] hover:text-blue-400 font-semibold transition-colors">Sign in</a>
            </p>
        </form>
    </x-authentication-card>
</x-guest-layout>
