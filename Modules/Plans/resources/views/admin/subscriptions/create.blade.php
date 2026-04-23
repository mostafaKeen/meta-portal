<x-company::layouts.master>
    <x-slot name="header">
        Assign Subscription
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6 border-b border-gray-50 pb-4">Manual Subscription Entry</h3>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                    <p class="font-bold text-sm">Please fix the following errors:</p>
                    <ul class="mt-2 text-xs list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Company Selection -->
                    <div>
                        <label for="company_id" class="block text-sm font-bold text-gray-700 mb-1">Target Company *</label>
                        <select name="company_id" id="company_id" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            <option value="">Select a Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }} ({{ $company->email }})</option>
                            @endforeach
                        </select>
                        @error('company_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Plan Selection -->
                    <div>
                        <label for="plan_id" class="block text-sm font-bold text-gray-700 mb-1">Subscription Plan *</label>
                        <select name="plan_id" id="plan_id" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            <option value="">Select an Active Plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ number_format($plan->price, 2) }} ({{ $plan->billing_cycle }})</option>
                            @endforeach
                        </select>
                        @error('plan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dates -->
                        <div>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            @error('starts_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="ends_at" class="block text-sm font-bold text-gray-700 mb-1">Expiry Date</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            @error('ends_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-[10px] text-gray-400 mt-1">Leave blank for infinite validity</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-bold text-gray-700 mb-1">Initial Status *</label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trialing" {{ old('status') == 'trialing' ? 'selected' : '' }}>Trialing</option>
                            <option value="past_due" {{ old('status') == 'past_due' ? 'selected' : '' }}>Past Due</option>
                            <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mt-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p class="text-xs text-gray-500">Creating a new subscription will automatically cancel any existing active subscriptions for the target company. Proceed with caution.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 border-t border-gray-50 pt-8 mt-8">
                    <a href="{{ route('admin.subscriptions.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-500/30 transition-all">
                        Assign Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-company::layouts.master>
