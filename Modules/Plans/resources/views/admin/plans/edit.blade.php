<x-company::layouts.master>
    <x-slot name="header">
        Edit Plan: {{ $plan->name }}
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6 border-b border-gray-50 pb-4">Modify Plan Details & Limits</h3>

            <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- General Info -->
                    <div class="col-span-2">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Plan Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="2" class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">{{ old('description', $plan->description) }}</textarea>
                    </div>

                    <!-- Pricing -->
                    <div>
                        <label for="price" class="block text-sm font-bold text-gray-700 mb-1">Monthly Price ($) *</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $plan->price) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                    </div>

                    <div>
                        <label for="billing_cycle" class="block text-sm font-bold text-gray-700 mb-1">Billing Cycle *</label>
                        <select name="billing_cycle" id="billing_cycle" class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                            <option value="monthly" {{ $plan->billing_cycle == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ $plan->billing_cycle == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>

                    <!-- Limits Section -->
                    <div class="col-span-2 mt-4">
                        <h4 class="text-sm font-bold text-[#4169E1] uppercase tracking-wider mb-4 border-l-4 border-[#4169E1] pl-3 bg-blue-50 py-1 rounded-r">Infrastructure Limits</h4>
                    </div>

                    <div>
                        <label for="max_qr_numbers" class="block text-sm font-bold text-gray-700 mb-1">Max QR Numbers *</label>
                        <input type="number" name="max_qr_numbers" id="max_qr_numbers" value="{{ old('max_qr_numbers', $plan->max_qr_numbers) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                    </div>

                    <div>
                        <label for="max_agents" class="block text-sm font-bold text-gray-700 mb-1">Max Agents *</label>
                        <input type="number" name="max_agents" id="max_agents" value="{{ old('max_agents', $plan->max_agents) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                    </div>

                    <div>
                        <label for="max_telegram_bots" class="block text-sm font-bold text-gray-700 mb-1">Max Telegram Bots *</label>
                        <input type="number" name="max_telegram_bots" id="max_telegram_bots" value="{{ old('max_telegram_bots', $plan->max_telegram_bots) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                        @error('max_telegram_bots')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-[10px] text-gray-400 mt-1">Use -1 for unlimited bots.</p>
                    </div>

                    <div class="col-span-2 mt-4">
                        <h4 class="text-sm font-bold text-[#4169E1] uppercase tracking-wider mb-4 border-l-4 border-[#4169E1] pl-3 bg-blue-50 py-1 rounded-r">API Message Quotas</h4>
                    </div>

                    <div>
                        <label for="max_session_messages" class="block text-sm font-bold text-gray-700 mb-1">Max Session (Inbound) *</label>
                        <input type="number" name="max_session_messages" id="max_session_messages" value="{{ old('max_session_messages', $plan->max_session_messages) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                    </div>

                    <div>
                        <label for="max_template_messages" class="block text-sm font-bold text-gray-700 mb-1">Max Template (Outbound) *</label>
                        <input type="number" name="max_template_messages" id="max_template_messages" value="{{ old('max_template_messages', $plan->max_template_messages) }}" required class="w-full rounded-xl border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] transition-all">
                    </div>

                    <div class="mt-4 flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-[#4169E1] focus:ring-[#4169E1] h-5 w-5">
                        <label for="is_active" class="ml-3 text-sm font-bold text-gray-700">Set as Active Plan</label>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 border-t border-gray-50 pt-8">
                    <a href="{{ route('admin.plans.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-[#4169E1] text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-500/30 transition-all hover:scale-[1.02]">
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-company::layouts.master>
