<x-company::layouts.master>
    <x-slot name="header">
        My WhatsApp Connections
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Assigned Connections</h2>
            <p class="text-slate-500 mt-1">Manage and view the WhatsApp numbers assigned to you by your administrator.</p>
        </div>

        @if($assignedNumbers->isEmpty())
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-12 text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-[#4169E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No Numbers Assigned</h3>
                <p class="text-slate-500 max-w-sm mx-auto">You haven't been assigned any WhatsApp connections yet. Please contact your company administrator to get access.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assignedNumbers as $number)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 {{ $number->isApi() ? 'bg-purple-50 text-purple-600' : 'bg-green-50 text-green-600' }} rounded-2xl flex items-center justify-center">
                                    @if($number->isApi())
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    @endif
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold leading-none {{ $number->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ strtoupper($number->status) }}
                                </span>
                            </div>

                            <h4 class="text-xl font-bold text-slate-900 mb-1">{{ $number->phone_number }}</h4>
                            <p class="text-sm text-slate-500 mb-4">{{ $number->app_name ?? $number->session_name ?? 'Primary Connection' }}</p>

                            <div class="flex items-center space-x-2 py-3 border-t border-slate-50">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Your Access:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold {{ $number->pivot->access_type === 'edit' ? 'bg-[#4169E1] text-white' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $number->pivot->access_type === 'edit' ? 'Full Access' : 'View Only' }}
                                </span>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-slate-50/50 flex justify-between items-center">
                            <span class="text-xs font-medium text-slate-500">Type: {{ strtoupper($number->type) }}</span>
                            @if($number->pivot->access_type === 'edit')
                                <a href="#" class="text-sm font-bold text-[#4169E1] hover:text-blue-800 transition-colors">Manage →</a>
                            @else
                                <span class="text-xs font-medium text-slate-400">Restricted</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-company::layouts.master>
