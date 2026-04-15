<x-company::layouts.master>
    <x-slot name="header">
        Company Details: {{ $company->name }}
    </x-slot>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('company.edit', $company) }}" class="px-4 py-2 bg-[#2563eb] text-white rounded-lg hover:bg-blue-700">Edit Company</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Main details -->
        <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center space-x-4 mb-6">
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="h-16 w-16 object-cover rounded-md">
                    @else
                        <div class="h-16 w-16 rounded-md bg-gray-200 flex items-center justify-center text-gray-500 text-2xl font-bold">
                            {{ substr($company->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h2>
                        <p class="text-gray-500">{{ $company->domain_slug }}</p>
                    </div>
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Contact Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->email ?: 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->phone ?: 'N/A' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->address ?: 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Integration Status -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Status & Integrations</h3>
            
            <div class="mb-4">
                <span class="text-sm text-gray-500 block mb-1">Status</span>
                @if($company->status === 'active')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                @elseif($company->status === 'suspended')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                @endif
            </div>

            <div class="mb-4">
                <span class="text-sm text-gray-500 block mb-1">Bitrix24 Connected</span>
                @if($company->b24_domain && $company->b24_access_token)
                    <span class="text-green-600 font-medium">Yes - {{ $company->b24_domain }}</span>
                @else
                    <span class="text-red-500">No</span>
                @endif
            </div>

            <div>
                <span class="text-sm text-gray-500 block mb-1">WhatsApp Provider</span>
                @if($company->qr && !$company->api)
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">QR Based</span>
                @elseif($company->api && !$company->qr)
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">API Based</span>
                @elseif($company->qr && $company->api)
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-semibold">QR & API Both</span>
                @else
                    <span class="text-gray-500 text-sm">None Configured</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Company Users list -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Linked Users ({{ $company->users_count }})</h3>
            
            <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $user->role) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            No users linked to this company yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

</x-company::layouts.master>
