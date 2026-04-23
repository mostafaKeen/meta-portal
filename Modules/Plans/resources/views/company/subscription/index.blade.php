<x-company::layouts.master>
    <x-slot name="header">
        My Subscription
    </x-slot>

    <div class="space-y-8">
        <!-- Active Subscription Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800">Current Plan</h3>
                        <p class="text-slate-500">Manage your subscription and limits.</p>
                    </div>
                    @if($subscription)
                        <span class="px-4 py-1.5 bg-green-100 text-green-800 text-xs font-bold rounded-full uppercase tracking-tighter">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    @else
                        <span class="px-4 py-1.5 bg-red-100 text-red-800 text-xs font-bold rounded-full uppercase tracking-tighter">
                            No Active Plan
                        </span>
                    @endif
                </div>

                @if($subscription)
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Plan Name</p>
                            <p class="text-lg font-bold text-[#4169E1]">{{ $subscription->plan->name }}</p>
                            <p class="text-xs text-slate-500">${{ number_format($subscription->plan->price, 2) }} / {{ $subscription->plan->billing_cycle }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">WhatsApp Numbers</p>
                            <p class="text-lg font-bold text-slate-800">{{ $subscription->plan->max_qr_numbers == -1 ? '∞' : $subscription->plan->max_qr_numbers }} Connections</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Agents</p>
                            <p class="text-lg font-bold text-slate-800">{{ $subscription->plan->max_agents == -1 ? '∞' : $subscription->plan->max_agents }} Active Seats</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Telegram Bots</p>
                            <p class="text-lg font-bold text-slate-800">{{ $subscription->plan->max_telegram_bots == -1 ? '∞' : $subscription->plan->max_telegram_bots }} Connections</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Expiry Date</p>
                            <p class="text-lg font-bold text-slate-800">{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'Lifetime' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('company.subscription.plans') }}" class="px-6 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-500/20 transition-all">
                            Change Plan / Renew
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-blue-50 text-[#4169E1] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-blue-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">You haven't subscribed yet</h4>
                        <p class="text-slate-500 mb-6">Choose a plan to unlock all features of MetaPortal.</p>
                        <a href="{{ route('company.subscription.plans') }}" class="px-8 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-500/20 transition-all">
                            View Pricing Plans
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Requests -->
        @if($pendingRequests->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <h3 class="text-xl font-bold text-slate-800 mb-6">Pending Requests</h3>
                <div class="space-y-4">
                    @foreach($pendingRequests as $req)
                    <div class="flex items-center justify-between p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-amber-500 shadow-sm mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Requesting <span class="text-[#4169E1]">{{ $req->plan->name }}</span> ({{ ucfirst($req->type) }})</p>
                                <p class="text-xs text-slate-500">Sent on {{ $req->created_at->format('M d, Y - H:i') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-white text-amber-600 text-[10px] font-bold rounded-lg uppercase border border-amber-100">
                            Awaiting Approval
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</x-company::layouts.master>
