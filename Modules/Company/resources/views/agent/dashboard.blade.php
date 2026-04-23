<x-company::layouts.master>
    <x-slot name="header">
        {{ __('Agent Dashboard') }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-[#4169E1] mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            
            <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Welcome back, {{ auth()->user()->name }}!
            </h3>
            
            <p class="mt-2 text-lg text-[#4169E1] font-medium">
                {{ auth()->user()->company->name ?? 'Your Company' }}
            </p>
            
            <div class="mt-10 max-w-md bg-gray-50 border border-dashed border-gray-300 rounded-2xl p-8">
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Operations Interface</h4>
                <p class="text-gray-500">Your specific agent workspace tools are being synchronized. All data from <span class="text-gray-900 font-semibold">{{ auth()->user()->company->name ?? 'your company' }}</span> will be available here shortly.</p>
                
                <div class="mt-6 flex justify-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse" style="animation-delay: 0.2s"></span>
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse" style="animation-delay: 0.4s"></span>
                </div>
            </div>
        </div>
    </div>
</x-company::layouts.master>
