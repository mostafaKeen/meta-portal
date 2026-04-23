<x-company::layouts.master>
    <x-slot name="header">
        User Management
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <!-- Search -->
                <form method="GET" action="{{ route('company.users.index') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="rounded-l-lg border-gray-300 focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                    <select name="role" class="border-l-0 border-gray-300 focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        <option value="">All Roles</option>
                        <option value="company_admin" {{ request('role') == 'company_admin' ? 'selected' : '' }}>Admin</option>
                        <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-700 hover:bg-gray-200">Search</button>
                </form>

                <div class="flex flex-col">
                    <a href="{{ route('company.users.create') }}" class="px-4 py-2 bg-[#2563eb] border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-blue-700 transition text-center">
                        Add New User
                    </a>
                    @php
                        $activePlan = auth()->user()->company->activePlan();
                    @endphp
                    @if($activePlan)
                        <span class="text-xs mt-1 text-right font-semibold {{ auth()->user()->company->hasReachedAgentLimit() ? 'text-red-500' : 'text-gray-400' }}">
                            Usage: {{ auth()->user()->company->users()->count() }} / {{ $activePlan->max_agents == -1 ? 'Unlimited' : $activePlan->max_agents }} Users
                        </span>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->name }}
                                @if(auth()->id() === $user->id)
                                    <span class="ml-2 text-xs text-gray-500">(You)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'company_admin')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Admin</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Agent</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('company.users.edit', $user) }}" class="text-[#2563eb] hover:text-blue-900 mr-3">Edit</a>
                                
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('company.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                No users found matching your criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-company::layouts.master>
