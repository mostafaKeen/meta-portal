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
                            <input type="password" name="password" id="password" autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4169E1] focus:ring focus:ring-[#4169E1] focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#4169E1] focus:ring focus:ring-[#4169E1] focus:ring-opacity-50">
                        </div>
                    </div>

                    @if($user->isAgent())
                    <div class="border-t pt-6 mt-6">
                        <div class="flex items-center space-x-2 mb-4">
                            <svg class="w-5 h-5 text-[#4169E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <h4 class="text-lg font-semibold text-gray-900">WhatsApp Access Control</h4>
                        </div>
                        <p class="text-sm text-gray-500 mb-6">Assign specific WhatsApp numbers to this agent and define their permission level.</p>

                        <div class="space-y-4">
                            @forelse($whatsappNumbers as $number)
                                <div class="flex items-center justify-between p-4 rounded-xl border {{ isset($assignedNumbers[$number->id]) ? 'border-[#4169E1] bg-blue-50/30' : 'border-gray-100 bg-gray-50/50' }} transition-all duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center h-5">
                                            <input type="hidden" name="whatsapp_numbers[{{ $number->id }}][assigned]" value="0">
                                            <input id="wa_{{ $number->id }}" name="whatsapp_numbers[{{ $number->id }}][assigned]" type="checkbox" value="1" {{ isset($assignedNumbers[$number->id]) ? 'checked' : '' }}
                                                class="h-5 w-5 text-[#4169E1] border-gray-300 rounded focus:ring-[#4169E1] cursor-pointer">
                                        </div>
                                        <div class="ml-3">
                                            <label for="wa_{{ $number->id }}" class="text-sm font-bold text-gray-900 cursor-pointer block">
                                                {{ $number->phone_number }}
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $number->isApi() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ strtoupper($number->type) }}
                                                </span>
                                            </label>
                                            <p class="text-xs text-gray-500">{{ $number->app_name ?? $number->session_name ?? 'Untitled Connection' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span class="text-xs font-medium text-gray-400">Permissions:</span>
                                        <select name="whatsapp_numbers[{{ $number->id }}][access_type]" 
                                            class="text-xs font-medium rounded-lg border-gray-200 focus:border-[#4169E1] focus:ring-[#4169E1] bg-white shadow-sm py-1.5 pl-3 pr-8">
                                            <option value="view" {{ (isset($assignedNumbers[$number->id]) && $assignedNumbers[$number->id] == 'view') ? 'selected' : '' }}>View Only</option>
                                            <option value="edit" {{ (isset($assignedNumbers[$number->id]) && $assignedNumbers[$number->id] == 'edit') ? 'selected' : '' }}>Full Access (Edit)</option>
                                        </select>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                    <p class="text-sm text-gray-500">No WhatsApp numbers configured for this company.</p>
                                    <a href="{{ route('company.whatsapp.create') }}" class="text-[#4169E1] text-sm font-semibold hover:underline mt-2 inline-block">Add your first number →</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endif
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
