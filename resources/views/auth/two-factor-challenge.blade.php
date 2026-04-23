<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-400" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <div class="mb-4 text-sm text-gray-400" x-cloak x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-label for="code" value="{{ __('Code') }}" class="text-gray-300 font-semibold" />
                    <x-input id="code" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="text" inputmode="numeric" name="code" autofocus autocomplete="one-time-code" placeholder="000000" />
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="{{ __('Recovery Code') }}" class="text-gray-300 font-semibold" />
                    <x-input id="recovery_code" class="block mt-1.5 w-full bg-white/5 border-white/10 text-white placeholder-gray-500 focus:border-[#4169E1] focus:ring-[#4169E1]" type="text" name="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <button type="button" class="text-sm text-gray-400 hover:text-[#4169E1] cursor-pointer font-medium transition-colors"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { document.getElementById('recovery_code').focus() })
                                    ">
                        {{ __('Use a recovery code') }}
                    </button>

                    <button type="button" class="text-sm text-gray-400 hover:text-[#4169E1] cursor-pointer font-medium transition-colors"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { document.getElementById('code').focus() })
                                    ">
                        {{ __('Use an authentication code') }}
                    </button>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition-all duration-200">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
