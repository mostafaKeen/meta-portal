<x-company::layouts.master>
    <x-slot name="header">
        {{ __('Company Dashboard') }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-[#4169E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->company->name ?? 'Company Overview' }}</h3>
                <p class="text-gray-500 text-sm">Manage your team and WhatsApp communication from here.</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50">
                <p class="text-sm font-medium text-gray-500 uppercase">Team Members</p>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-[#4169E1] mt-1">{{ auth()->user()->company->users()->count() ?? 0 }}</p>
                    @if($activePlan = auth()->user()->company->activePlan())
                        <span class="text-xs text-gray-400 mb-1">Limit: {{ $activePlan->max_agents == -1 ? '∞' : $activePlan->max_agents }}</span>
                    @endif
                </div>
            </div>
            <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50">
                <p class="text-sm font-medium text-gray-500 uppercase">WhatsApp QR Lines</p>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-[#4169E1] mt-1">{{ auth()->user()->company->whatsappNumbers()->where('type', 'qr')->count() ?? 0 }}</p>
                    @if($activePlan)
                        <span class="text-xs text-gray-400 mb-1">Limit: {{ $activePlan->max_qr_numbers == -1 ? '∞' : $activePlan->max_qr_numbers }}</span>
                    @endif
                </div>
            </div>
            @if($subscription = auth()->user()->company->subscription)
            <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50">
                <p class="text-sm font-medium text-gray-500 uppercase">Session Messages</p>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $subscription->session_messages_count ?? 0 }}</p>
                    @if($activePlan)
                        <span class="text-xs text-gray-400 mb-1">Limit: {{ $activePlan->max_session_messages == -1 ? '∞' : $activePlan->max_session_messages }}</span>
                    @endif
                </div>
            </div>
            <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50">
                <p class="text-sm font-medium text-gray-500 uppercase">Template Messages</p>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $subscription->template_messages_count ?? 0 }}</p>
                    @if($activePlan)
                        <span class="text-xs text-gray-400 mb-1">Limit: {{ $activePlan->max_template_messages == -1 ? '∞' : $activePlan->max_template_messages }}</span>
                    @endif
                </div>
            </div>
            @endif
            <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50">
                <p class="text-sm font-medium text-gray-500 uppercase">Telegram Bots</p>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-[#0088cc] mt-1">{{ auth()->user()->company->telegramBots()->count() ?? 0 }}</p>
                    @php
                        $activePlan = $activePlan ?? auth()->user()->company->activePlan();
                    @endphp
                    @if($activePlan)
                        <span class="text-xs text-gray-400 mb-1">Limit: {{ $activePlan->max_telegram_bots == -1 ? '∞' : $activePlan->max_telegram_bots }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-company::layouts.master>
