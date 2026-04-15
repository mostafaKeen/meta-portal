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

            <!-- Column 2: Bitrix24 -->
            <div class="xl:col-span-1">
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

                        <!-- Info Box -->
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                            <div class="flex">
                                <svg class="h-5 w-5 text-amber-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="text-xs font-semibold text-amber-800">Heads up</p>
                                    <p class="text-xs text-amber-700 mt-0.5">Changing REST credentials may require re-authenticating the Bitrix24 integration.</p>
                                </div>
                            </div>
                        </div>
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
