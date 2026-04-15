<x-company::layouts.master>
    <x-slot name="header">
        Add WhatsApp Number
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('company.whatsapp.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#2563eb] transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to WhatsApp Numbers
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-bold text-gray-800">New WhatsApp Connection</h3>
                <p class="text-sm text-gray-500 mt-0.5">Configure either an API-based or QR-based connection.</p>
            </div>

            <form action="{{ route('company.whatsapp.store') }}" method="POST" class="p-6">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Type Selector -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Connection Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="api" id="type_api" {{ old('type', 'api') === 'api' ? 'checked' : '' }} class="peer sr-only" onchange="toggleFields()">
                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-[#2563eb] peer-checked:bg-blue-50 transition-all text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-[#2563eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                <span class="text-sm font-bold text-gray-800">Cloud API</span>
                                <p class="text-xs text-gray-500 mt-0.5">Official Meta API</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="qr" id="type_qr" {{ old('type') === 'qr' ? 'checked' : '' }} class="peer sr-only" onchange="toggleFields()">
                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                <span class="text-sm font-bold text-gray-800">QR Web</span>
                                <p class="text-xs text-gray-500 mt-0.5">Scan-based link</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Common Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required placeholder="+971XXXXXXXXX" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm font-mono">
                        <p class="text-xs text-gray-500 mt-1">Must be globally unique across all companies.</p>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- API Fields -->
                <div id="api_fields" class="space-y-4 mb-6">
                    <h4 class="text-sm font-bold text-indigo-700 uppercase tracking-wider border-b border-indigo-100 pb-2">API Configuration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700">App Name <span class="text-red-500">*</span></label>
                            <input type="text" name="app_name" id="app_name" value="{{ old('app_name') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                        </div>
                        <div>
                            <label for="app_id" class="block text-sm font-medium text-gray-700">App ID <span class="text-red-500">*</span></label>
                            <input type="text" name="app_id" id="app_id" value="{{ old('app_id') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm font-mono">
                        </div>
                    </div>
                    <div>
                        <label for="app_token" class="block text-sm font-medium text-gray-700">App Token <span class="text-red-500">*</span></label>
                        <input type="password" name="app_token" id="app_token" value="{{ old('app_token') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                    </div>
                </div>

                <!-- QR Fields -->
                <div id="qr_fields" class="space-y-4 mb-6" style="display: none;">
                    <h4 class="text-sm font-bold text-green-700 uppercase tracking-wider border-b border-green-100 pb-2">QR Configuration</h4>
                    <div>
                        <label for="session_name" class="block text-sm font-medium text-gray-700">Session Name <span class="text-red-500">*</span></label>
                        <input type="text" name="session_name" id="session_name" value="{{ old('session_name') }}" placeholder="e.g. sales-line-1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50 text-sm">
                        <p class="text-xs text-gray-500 mt-1">A friendly name to identify this QR session.</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-5 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('company.whatsapp.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-[#2563eb] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Number
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFields() {
            const apiRadio = document.getElementById('type_api');
            const apiFields = document.getElementById('api_fields');
            const qrFields = document.getElementById('qr_fields');

            if (apiRadio.checked) {
                apiFields.style.display = 'block';
                qrFields.style.display = 'none';
            } else {
                apiFields.style.display = 'none';
                qrFields.style.display = 'block';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
</x-company::layouts.master>
