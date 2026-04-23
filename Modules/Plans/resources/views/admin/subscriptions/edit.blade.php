<x-company::layouts.master>
    <x-slot name="header">
        Adjust Subscription: {{ $subscription->company->name }}
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6 border-b border-gray-50 pb-4">Modify Current Subscription</h3>

            <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Read-only Company -->
                    <div>
                        <label class="block text-sm font-bold text-gray-400 mb-1">Company</label>
                        <div class="w-full rounded-xl border-gray-100 bg-gray-50 px-4 py-3 text-gray-500 font-medium">
                            {{ $subscription->company->name }}
                        </div>
                    </div>

                    <!-- Plan Selection -->
                    <div>
                        <label for="plan_id" class="block text-sm font-bold text-gray-700 mb-1">Subscription Plan *</label>
                        <select name="plan_id" id="plan_id" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ $subscription->plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - ${{ number_format($plan->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dates -->
                        <div>
                            <label for="starts_at" class="block text-sm font-bold text-gray-700 mb-1">Start Date *</label>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ $subscription->starts_at->format('Y-m-d\TH:i') }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-bold text-gray-700 mb-1">Expiry Date</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-bold text-gray-700 mb-1">Current Status *</label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trialing" {{ $subscription->status == 'trialing' ? 'selected' : '' }}>Trialing</option>
                            <option value="past_due" {{ $subscription->status == 'past_due' ? 'selected' : '' }}>Past Due</option>
                            <option value="canceled" {{ $subscription->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 border-t border-gray-50 pt-8 mt-8">
                    <a href="{{ route('admin.subscriptions.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-500/30 transition-all">
                        Update Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-company::layouts.master>
