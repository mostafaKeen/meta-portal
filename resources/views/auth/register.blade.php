<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Full Name') }}" class="text-gray-300 font-semibold" />
                <x-input id="name" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            </div>

            <div class="mt-5">
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-300 font-semibold" />
                <x-input id="email" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            </div>

            <div class="mt-5">
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-300 font-semibold" />
                <x-input id="password" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            </div>

            <div class="mt-5">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-300 font-semibold" />
                <x-input id="password_confirmation" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-5">
                    <label for="terms" class="flex items-center">
                        <x-checkbox name="terms" id="terms" required class="bg-white/5 border-white/10 text-[#4169E1] focus:ring-[#4169E1]" />
                        <span class="ms-2 text-sm text-gray-400">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-[#4169E1] hover:text-blue-400 font-medium">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-[#4169E1] hover:text-blue-400 font-medium">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </span>
                    </label>
                </div>
            @endif

            <div class="mt-6">
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4169E1] focus:ring-offset-2 focus:ring-offset-gray-900">
                    {{ __('Create Account') }}
                </button>
            </div>

            <p class="mt-6 text-center text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-[#4169E1] hover:text-blue-400 font-semibold transition-colors">Sign in</a>
            </p>
        </form>
    </x-authentication-card>
</x-guest-layout>
