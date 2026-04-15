<x-company::layouts.master>
    <x-slot name="header">
        Create Company
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            
            <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- General Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">General Information</h3>
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Company Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="domain_slug" class="block text-sm font-medium text-gray-700">Domain Slug <span class="text-red-500">*</span></label>
                            <input type="text" name="domain_slug" id="domain_slug" value="{{ old('domain_slug') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Unique identifier (alpha-dash only).</p>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">{{ old('address') }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <input type="file" name="logo" id="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#2563eb] hover:file:bg-blue-100">
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>

                    <!-- Integrations -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Bitrix24 Integration</h3>
                        
                        <div class="mb-4">
                            <label for="b24_domain" class="block text-sm font-medium text-gray-700">Bitrix24 Domain</label>
                            <input type="text" name="b24_domain" id="b24_domain" value="{{ old('b24_domain') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="b24_client_id" class="block text-sm font-medium text-gray-700">Client ID</label>
                            <input type="text" name="b24_client_id" id="b24_client_id" value="{{ old('b24_client_id') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="b24_client_secret" class="block text-sm font-medium text-gray-700">Client Secret</label>
                            <input type="password" name="b24_client_secret" id="b24_client_secret" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Primary Admin Account</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="admin_name" class="block text-sm font-medium text-gray-700">Admin Name <span class="text-red-500">*</span></label>
                            <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="admin_email" class="block text-sm font-medium text-gray-700">Admin Email <span class="text-red-500">*</span></label>
                            <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="admin_password" class="block text-sm font-medium text-gray-700">Admin Password <span class="text-red-500">*</span></label>
                            <input type="password" name="admin_password" id="admin_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div class="mb-4">
                            <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Admin Password <span class="text-red-500">*</span></label>
                            <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('company.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2563eb]">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#2563eb] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2563eb]">
                        Create Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-company::layouts.master>
