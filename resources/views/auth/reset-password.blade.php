<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-300 font-semibold" />
                <x-input id="email" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-5">
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-300 font-semibold" />
                <x-input id="password" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            </div>

            <div class="mt-5">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-300 font-semibold" />
                <x-input id="password_confirmation" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition-all duration-200">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
