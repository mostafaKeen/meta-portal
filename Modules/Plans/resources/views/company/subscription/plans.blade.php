<x-company::layouts.master>
    <x-slot name="header">
        Choose a Plan
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-900">Scale Your Business</h2>
            <p class="text-lg text-slate-500 mt-4">Select the plan that best fits your current operations.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
            @php
                $isActive = $activeSubscription && $activeSubscription->plan_id == $plan->id;
                $hasPendingRequest = in_array($plan->id, $pendingRequests);
            @endphp
            <div class="bg-white rounded-3xl p-8 border {{ $isActive ? 'border-[#4169E1] ring-2 ring-blue-100' : ($hasPendingRequest ? 'border-amber-200' : 'border-gray-100 shadow-sm') }} flex flex-col hover:border-[#4169E1] transition-all hover:translate-y-[-4px] relative">
                @if($isActive)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-[#4169E1] text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-blue-500/30">
                        Current Active Plan
                    </div>
                @elseif($hasPendingRequest)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-amber-500 text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-amber-500/30">
                        Request Pending
                    </div>
                @endif

                <div class="mb-8">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $plan->name }}</h3>
                    <p class="text-sm text-slate-500 h-12">{{ $plan->description }}</p>
                    <div class="mt-6 flex items-baseline">
                        <span class="text-4xl font-extrabold text-slate-900">${{ number_format($plan->price, 2) }}</span>
                        <span class="text-slate-400 ml-2 font-medium">/ {{ $plan->billing_cycle }}</span>
                    </div>
                </div>

                <ul class="space-y-4 mb-10 flex-grow">
                    <li class="flex items-center text-sm font-medium text-slate-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ $plan->max_qr_numbers }} WhatsApp Connections
                    </li>
                    <li class="flex items-center text-sm font-medium text-slate-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ $plan->max_agents }} Active Agents
                    </li>
                    <li class="flex items-center text-sm font-medium text-slate-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ number_format($plan->max_session_messages) }} Monthly Messages
                    </li>
                    <li class="flex items-center text-sm font-medium text-slate-600">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ $plan->max_telegram_bots == -1 ? 'Unlimited' : $plan->max_telegram_bots }} Telegram Bots
                    </li>
                </ul>

                @if($isActive)
                    <button type="button" disabled class="w-full py-4 bg-slate-100 text-slate-400 font-bold rounded-2xl cursor-not-allowed">
                        Already Subscribed
                    </button>
                @elseif($hasPendingRequest)
                    <button type="button" disabled class="w-full py-4 bg-amber-50 text-amber-500 font-bold rounded-2xl cursor-not-allowed border border-amber-100">
                        Awaiting Approval
                    </button>
                @else
                    <button type="button" 
                            onclick="openRequestModal('{{ $plan->id }}', '{{ $plan->name }}')" 
                            class="w-full py-4 bg-[#4169E1] text-white shadow-lg shadow-blue-500/20 font-bold rounded-2xl hover:bg-blue-800 transition-all">
                        Request This Plan
                    </button>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Request Modal -->
    <div id="requestModal" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeRequestModal()"></div>
            
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Send Subscription Request</h3>
                    <p class="text-sm text-slate-500 mb-6">Requesting: <span id="modalPlanName" class="text-[#4169E1] font-bold"></span></p>

                    <form action="{{ route('company.subscription.request') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" id="modalPlanId">
                        
                        <div class="mb-6">
                            <label for="user_notes" class="block text-sm font-bold text-slate-700 mb-2">Message to Administrator (Optional)</label>
                            <textarea name="user_notes" id="user_notes" rows="4" placeholder="e.g. requesting upgrade before month-end..." class="w-full rounded-2xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all"></textarea>
                        </div>

                        <div class="flex space-x-4">
                            <button type="button" onclick="closeRequestModal()" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-500/20 transition-all">
                                Send Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openRequestModal(id, name) {
            document.getElementById('modalPlanId').value = id;
            document.getElementById('modalPlanName').innerText = name;
            document.getElementById('requestModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRequestModal() {
            document.getElementById('requestModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const planId = urlParams.get('plan');
            if (planId) {
                // Find the plan button and trigger its name
                // Alternatively, we can pass the name too, but let's try to find it in the DOM
                // Or just use the ID and we can look up the name from the foreach loop if we had it
                // For simplicity, I'll update the public link to include the name or just look it up here
                // Let's just use the planId and look for the element that has it
                const buttons = document.querySelectorAll('button[onclick*="openRequestModal"]');
                buttons.forEach(btn => {
                    if (btn.getAttribute('onclick').includes("'" + planId + "'")) {
                        btn.click();
                    }
                });
            }
        }
    </script>
    @endpush
</x-company::layouts.master>
