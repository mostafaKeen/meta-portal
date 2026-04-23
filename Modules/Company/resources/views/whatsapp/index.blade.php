<x-company::layouts.master>
    <x-slot name="header">
        WhatsApp Numbers
    </x-slot>

    <!-- Header Card -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manage WhatsApp Numbers</h2>
            <p class="text-sm text-gray-500 mt-1">Configure API and QR-based WhatsApp connections for your company.</p>
            @php
                $activePlan = auth()->user()->company->activePlan();
            @endphp
            @if($activePlan)
                <p class="text-xs font-semibold {{ auth()->user()->company->hasReachedWhatsappLimit() ? 'text-red-500' : 'text-gray-400' }} mt-1">
                    Usage: {{ auth()->user()->company->whatsappNumbers()->where('type', 'qr')->count() }} / {{ $activePlan->max_qr_numbers == -1 ? 'Unlimited' : $activePlan->max_qr_numbers }} WhatsApp QR Numbers
                </p>
            @endif
        </div>
        <a href="{{ route('company.whatsapp.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#2563eb] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Number
        </a>
    </div>

    @if($numbers->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($numbers as $number)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:border-gray-200 transition-all duration-200">
            <!-- Card Header -->
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between {{ $number->isApi() ? 'bg-gradient-to-r from-indigo-50 to-blue-50' : 'bg-gradient-to-r from-green-50 to-emerald-50' }}">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ $number->isApi() ? 'bg-indigo-100 text-indigo-600' : 'bg-green-100 text-green-600' }}">
                        @if($number->isApi())
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider {{ $number->isApi() ? 'text-indigo-600' : 'text-green-600' }}">
                            {{ $number->type }}
                        </span>
                        <p class="text-sm font-bold text-gray-900">{{ $number->isApi() ? $number->app_name : $number->session_name }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $number->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($number->status) }}
                </span>
            </div>

            <!-- Card Body -->
            <div class="px-5 py-4 space-y-3">
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 text-gray-400 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span class="font-mono font-medium text-gray-800">{{ $number->phone_number }}</span>
                </div>

                @if($number->isApi())
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 text-gray-400 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <span class="text-gray-600 truncate">ID: {{ $number->app_id }}</span>
                </div>
                @endif
            </div>

            <!-- Card Actions -->
            <div class="px-5 py-3 border-t border-gray-50 bg-gray-50/50 flex items-center justify-end space-x-2">
                <a href="{{ route('company.whatsapp.edit', $number) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:text-[#2563eb] hover:border-blue-200 hover:bg-blue-50 transition-all">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <form action="{{ route('company.whatsapp.destroy', $number) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this number?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">No WhatsApp numbers configured</h3>
        <p class="text-sm text-gray-500 mb-6">Get started by adding your first API or QR-based WhatsApp connection.</p>
        <a href="{{ route('company.whatsapp.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#2563eb] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add First Number
        </a>
    </div>
    @endif
</x-company::layouts.master>
