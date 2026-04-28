<x-company::layouts.master>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Telegram Bots') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('company.telegram.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    {{ __('Manage Bots') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bots as $bot)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $bot->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $bot->status }}
                        </span>
                    </div>

                    <h3 class="text-xl font-extrabold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $bot->name }}</h3>
                    <p class="text-gray-500 text-sm mb-6 font-medium">@ {{ Str::limit($bot->token, 15) }}...</p>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                            <p class="text-2xl font-black text-gray-900">{{ $bot->chats_count }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Total Chats</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4 text-center">
                            <p class="text-2xl font-black text-blue-600">Active</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Status</p>
                        </div>
                    </div>

                    <a href="{{ route('telegram.chats', $bot) }}" class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-gray-900 text-white rounded-2xl font-bold text-sm hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300">
                        <span>Open Dashboard</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center">
                <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Bots Configured</h3>
                <p class="text-gray-500 mb-8 max-w-sm text-center">Start by connecting your first Telegram bot in the management section.</p>
                <a href="{{ route('company.telegram.index') }}" class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-colors">
                    Add Bot Now
                </a>
            </div>
        @endforelse
    </div>
</x-company::layouts.master>
