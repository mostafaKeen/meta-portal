<x-company::layouts.master>
    <x-slot name="header">
        Company Configurations
    </x-slot>

    <!-- Company Name Header Card -->
    <div class="mb-8 bg-gradient-to-r from-[#2563eb] to-blue-800 rounded-2xl p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/10">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-1/2 -mb-8 w-60 h-60 bg-white/5 rounded-full blur-3xl"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-blue-200 text-sm font-medium uppercase tracking-wider mb-1">Managing</p>
                <h2 class="text-3xl font-extrabold tracking-tight">{{ $company->name }}</h2>
                <p class="text-blue-200 text-sm mt-1">Configure your integrations and company profile below.</p>
            </div>
            @if($company->logo)
            <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="h-16 w-16 object-cover rounded-xl border-2 border-white/20 shadow-lg">
            @endif
        </div>
    </div>

    <form action="{{ route('company.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold text-sm">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm pl-7 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- Column 1: Contact & Branding -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-[#2563eb] flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Contact & Branding</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Logo Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Company Logo</label>
                            <div class="flex items-center space-x-4">
                                @if($company->logo)
                                    <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="h-14 w-14 object-cover rounded-xl border-2 border-gray-200 shadow-sm">
                                @else
                                    <div class="h-14 w-14 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-xl border-2 border-dashed border-gray-300">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <input type="file" name="logo" class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#2563eb] hover:file:bg-blue-100 file:cursor-pointer file:transition-colors">
                            </div>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Support Email</label>

                            <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm">
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50 text-sm">{{ old('address', $company->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 2: Bitrix24 & Meta CAPI -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Bitrix24 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Bitrix24 CRM</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="b24_domain" class="block text-sm font-medium text-gray-700">Portal Domain</label>
                            <div class="mt-1 flex rounded-lg shadow-sm overflow-hidden">
                                <span class="inline-flex items-center px-3 border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-xs font-medium">https://</span>
                                <input type="text" name="b24_domain" id="b24_domain" value="{{ old('b24_domain', $company->b24_domain) }}" placeholder="yourcompany.bitrix24.com" class="flex-1 block w-full rounded-none rounded-r-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="b24_client_id" class="block text-sm font-medium text-gray-700">Application ID</label>
                            <input type="text" name="b24_client_id" id="b24_client_id" value="{{ old('b24_client_id', $company->b24_client_id) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm font-mono">
                        </div>

                        <div>
                            <label for="b24_client_secret" class="block text-sm font-medium text-gray-700">Application Key</label>
                            <input type="password" name="b24_client_secret" id="b24_client_secret" value="{{ old('b24_client_secret', $company->b24_client_secret) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Meta Conversion API -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Meta Conversion API</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="fb_pixel_id" class="block text-sm font-medium text-gray-700">Meta Pixel ID</label>
                            <input type="text" name="fb_pixel_id" id="fb_pixel_id" value="{{ old('fb_pixel_id', $company->fb_pixel_id) }}" placeholder="e.g. 1234567890" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm font-mono">
                        </div>

                        <div>
                            <label for="fb_access_token" class="block text-sm font-medium text-gray-700">Conversion API Access Token</label>
                            <textarea name="fb_access_token" id="fb_access_token" rows="3" placeholder="EAAB..." class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm font-mono">{{ old('fb_access_token', $company->fb_access_token) }}</textarea>
                        </div>

                        @if($company->capi_outbound_token)
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bitrix24 Outbound Webhook URL</label>
                            <div class="flex items-center space-x-2" x-data="{ copied: false }">
                                <input type="text" id="capi_webhook_url" readonly value="{{ route('api.capi.webhook', $company->capi_outbound_token) }}" class="flex-1 bg-white border-gray-200 rounded-lg text-[10px] font-mono py-2 focus:ring-0 focus:border-gray-200">
                                <button type="button" 
                                    @click="
                                        navigator.clipboard.writeText('{{ route('api.capi.webhook', $company->capi_outbound_token) }}');
                                        copied = true;
                                        setTimeout(() => copied = false, 2000)
                                    "
                                    class="p-2 rounded-lg transition-all duration-200"
                                    :class="copied ? 'text-green-600 bg-green-50' : 'text-blue-600 hover:bg-blue-50'"
                                >
                                    <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                    </svg>
                                    <svg x-show="copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>

                            <p class="text-[10px] text-gray-500 mt-2">Use this URL in Bitrix24 Automation Rules (Webhooks) to send events to Meta.</p>
                        </div>
                        @else
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center justify-between">
                            <p class="text-xs text-blue-700">Outbound token not generated yet.</p>
                            <a href="{{ route('capi.token.generate') }}" class="text-xs font-bold text-blue-600 hover:underline">Generate Token</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Save Button -->
        <div class="mt-8 flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center py-3 px-8 border border-transparent shadow-lg text-sm font-bold rounded-xl text-white bg-[#2563eb] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2563eb] transition-all duration-200 hover:shadow-xl hover:shadow-blue-500/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Save Configuration
            </button>
        </div>
    </form>
</x-company::layouts.master>
