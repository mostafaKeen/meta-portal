<x-company::layouts.master>
    <x-slot name="header">
        Active Subscriptions
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Company Subscription Overview</h3>
        <a href="{{ route('admin.subscriptions.create') }}" class="inline-flex items-center px-4 py-2 bg-[#4169E1] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Assign New Subscription
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Current Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Billing Period</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#4169E1] font-bold text-xs border border-blue-100 uppercase">
                                        {{ substr($subscription->company->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-gray-900">{{ $subscription->company->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $subscription->company->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="font-semibold text-slate-800">{{ $subscription->plan->name }}</div>
                                <div class="text-xs text-gray-500">${{ number_format($subscription->plan->price, 2) }} / {{ $subscription->plan->billing_cycle }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-xs">Start: {{ $subscription->starts_at->format('M d, Y') }}</div>
                                <div class="text-xs">Ends: {{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'Life-time/Ongoing' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                    @if($subscription->status == 'active') bg-green-100 text-green-800 
                                    @elseif($subscription->status == 'trialing') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="text-[#4169E1] hover:text-blue-900 font-bold">Adjust</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</x-company::layouts.master>
