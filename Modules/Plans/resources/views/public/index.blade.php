<x-layouts.public>
    <x-slot name="title">Pricing Plans</x-slot>

    @push('styles')
    <style>
        .pricing-gradient {
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
        }

        .pricing-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .pricing-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 60px -12px rgba(65, 105, 225, 0.15);
        }
    </style>
    @endpush

    <!-- Header Section -->
    <div class="pt-32 pb-16 lg:pt-48 lg:pb-24 pricing-gradient overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
                Simple, Transparent <span class="text-gradient">Pricing</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto mb-16">
                Choose the perfect plan for your business needs. Scale your WhatsApp integrations and marketing workflows with confidence.
            </p>

            @php
                $activePlanId = auth()->check() ? (auth()->user()->company?->activePlan()?->id) : null;
            @endphp

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch pt-8">
                @foreach($plans as $plan)
                    @php
                        $isCurrent = $activePlanId && $activePlanId == $plan->id;
                    @endphp
                    <div class="pricing-card flex flex-col bg-white rounded-3xl p-8 border {{ $isCurrent ? 'border-[#4169E1] ring-2 ring-blue-50 shadow-lg' : 'border-slate-100 shadow-sm' }} relative">
                        @if($isCurrent)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-[#4169E1] text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-blue-500/30">
                                Your Current Plan
                            </div>
                        @endif

                        <div class="mb-8 items-start">
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ $plan->name }}</h3>
                            <p class="text-sm text-slate-500 h-10">{{ $plan->description }}</p>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-baseline">
                                <span class="text-4xl font-extrabold text-slate-900">${{ $plan->price }}</span>
                                <span class="text-slate-400 ml-2">/ {{ $plan->billing_cycle }}</span>
                            </div>
                        </div>

                        <div class="space-y-4 mb-10 flex-grow">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="ml-3 text-sm font-semibold text-slate-700"><strong>{{ $plan->max_qr_numbers == -1 ? '∞' : $plan->max_qr_numbers }}</strong> WhatsApp Connections</span>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="ml-3 text-sm font-semibold text-slate-700">Up to <strong>{{ $plan->max_agents == -1 ? '∞' : $plan->max_agents }}</strong> Agents</span>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="ml-3 text-sm font-medium text-slate-600"><strong>{{ number_format($plan->max_session_messages) }}</strong> Inbound Messages</span>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="ml-3 text-sm font-medium text-slate-600"><strong>{{ number_format($plan->max_template_messages) }}</strong> Export Templates</span>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-5 w-5 text-green-500">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="ml-3 text-sm font-semibold text-slate-700">Up to <strong>{{ $plan->max_telegram_bots == -1 ? 'Unlimited' : $plan->max_telegram_bots }}</strong> Telegram Bots</span>
                            </div>
                        </div>

                        @if($isCurrent)
                            <button disabled class="w-full inline-flex items-center justify-center px-6 py-4 bg-slate-100 text-slate-400 font-bold rounded-2xl cursor-not-allowed shadow-sm">
                                Your Current Plan
                            </button>
                        @else
                            <a href="{{ auth()->check() ? route('company.subscription.plans', ['plan' => $plan->id]) : route('login') }}" class="w-full inline-flex items-center justify-center px-6 py-4 bg-slate-50 text-[#4169E1] hover:bg-blue-50 font-bold rounded-2xl transition-all duration-200 shadow-sm">
                                {{ auth()->check() ? 'Change To This Plan' : 'Get This Plan' }}
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="py-24 bg-white relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-slate-900 text-center mb-12">Common Questions</h2>
            <div class="space-y-8">
                <div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Can I upgrade my plan later?</h4>
                    <p class="text-slate-600">Absolutely! You can upgrade or downgrade your plan at any time through your company dashboard. Changes take effect on the next billing cycle.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">How do WhatsApp QR connections work?</h4>
                    <p class="text-slate-600">You simply scan a QR code from your mobile WhatsApp app, and it links your session to our platform, allowing multi-agent access within Bitrix24.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Is there any extra cost for WhatsApp Official API?</h4>
                    <p class="text-slate-600">Meta charges for official API messages separately. Our platform covers the integration infrastructure, but message costs are billed by Meta directly.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
