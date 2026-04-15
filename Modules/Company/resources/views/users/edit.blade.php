<x-company::layouts.master>
    <x-slot name="header">
        Edit User: {{ $user->name }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-3xl mx-auto">
        <div class="p-6 bg-white border-b border-gray-200">
            
            <form action="{{ route('company.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                            <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="company_admin" {{ old('role', $user->role) == 'company_admin' ? 'selected' : '' }}>Company Admin</option>
                        </select>
                        @if(auth()->id() === $user->id)
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <p class="text-xs text-orange-500 mt-1">You cannot change your own role.</p>
                        @endif
                    </div>

                    <div class="border-t pt-4 mt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-2">Change Password</h4>
                        <p class="text-sm text-gray-500 mb-4">Leave blank if you don't want to change the password.</p>
                        
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password" autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2563eb] focus:ring focus:ring-[#2563eb] focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('company.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2563eb]">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#2563eb] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2563eb]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-company::layouts.master>
