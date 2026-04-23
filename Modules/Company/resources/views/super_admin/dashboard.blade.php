<x-company::layouts.master>
    <x-slot name="header">
        {{ __('Super Admin Dashboard') }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-[#4169E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">System Overview</h3>
                <p class="text-gray-500 text-sm">Welcome to the Meta Portal administrative control center.</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50">
                <p class="text-sm font-medium text-gray-500 uppercase">Total Companies</p>
                <p class="text-3xl font-extrabold text-[#4169E1] mt-1">{{ \Modules\Company\Models\Company::count() }}</p>
            </div>
            <!-- Add more stats as needed -->
        </div>
    </div>
</x-company::layouts.master>
