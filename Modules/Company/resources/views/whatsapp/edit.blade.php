<x-company::layouts.master>
    <x-slot name="header">
        Edit WhatsApp Number
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('company.whatsapp.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#2563eb] transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to WhatsApp Numbers
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 {{ $whatsapp->isApi() ? 'bg-gradient-to-r from-indigo-50 to-blue-50' : 'bg-gradient-to-r from-green-50 to-emerald-50' }}">
                <h3 class="text-lg font-bold text-gray-800">Edit: {{ $whatsapp->isApi() ? $whatsapp->app_name : $whatsapp->session_name }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $whatsapp->phone_number }} — {{ strtoupper($whatsapp->type) }} Connection</p>
            </div>

            <form action="{{ route('company.whatsapp.update', $whatsapp) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Type (read-only display + hidden input) -->
                <input type="hidden" name="type" value="{{ $whatsapp->type }}">

                <!-- Common Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $whatsapp->phone_number) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm font-mono">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm">
                            <option value="active" {{ old('status', $whatsapp->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $whatsapp->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                @if($whatsapp->isApi())
                <!-- API Fields -->
                <div class="space-y-4 mb-6">
                    <h4 class="text-sm font-bold text-indigo-700 uppercase tracking-wider border-b border-indigo-100 pb-2">API Configuration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700">App Name <span class="text-red-500">*</span></label>
                            <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $whatsapp->app_name) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                        </div>
                        <div>
                            <label for="app_id" class="block text-sm font-medium text-gray-700">App ID <span class="text-red-500">*</span></label>
                            <input type="text" name="app_id" id="app_id" value="{{ old('app_id', $whatsapp->app_id) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm font-mono">
                        </div>
                    </div>
                    <div>
                        <label for="app_token" class="block text-sm font-medium text-gray-700">App Token <span class="text-red-500">*</span></label>
                        <input type="password" name="app_token" id="app_token" value="{{ old('app_token', $whatsapp->app_token) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                    </div>
                </div>
                @else
                <!-- QR Fields -->
                <div class="space-y-4 mb-6">
                    <h4 class="text-sm font-bold text-green-700 uppercase tracking-wider border-b border-green-100 pb-2">QR Configuration</h4>
                    <div>
                        <label for="session_name" class="block text-sm font-medium text-gray-700">Session Name <span class="text-red-500">*</span></label>
                        <input type="text" name="session_name" id="session_name" value="{{ old('session_name', $whatsapp->session_name) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 text-sm">
                        <p class="text-xs text-gray-500 mt-1">A friendly name to identify this QR session.</p>
                    </div>
                </div>
                @endif

                <!-- Submit -->
                <div class="pt-5 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('company.whatsapp.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-[#2563eb] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-company::layouts.master>
