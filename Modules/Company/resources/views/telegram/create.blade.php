<x-company::layouts.master>
    <x-slot name="header">
        Add Telegram Bot
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('company.telegram.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Telegram Bots
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">New Telegram Bot</h3>
                <p class="text-sm text-gray-500">Enter your bot's token and name to get started.</p>
            </div>

            <form action="{{ route('company.telegram.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Bot Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Support Bot" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors" required>
                    @error('name') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="token" class="block text-sm font-semibold text-gray-700 mb-1.5">Bot Token</label>
                    <input type="text" name="token" id="token" value="{{ old('token') }}" placeholder="123456789:ABCDefGhIJKlmNoPQRsTUVwXyZ" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-mono transition-colors" required>
                    @error('token') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    <p class="mt-1.5 text-xs text-gray-500">Get your bot token from <a href="https://t.me/BotFather" target="_blank" class="text-blue-600 hover:underline font-medium">@BotFather</a> on Telegram.</p>
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                    <select name="status" id="status" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-5 py-3 bg-[#0088cc] text-white text-sm font-semibold rounded-xl hover:bg-blue-600 shadow-lg shadow-blue-500/20 transition-all duration-200">
                        Add Telegram Bot
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-company::layouts.master>
