<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-50 text-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Meta Portal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:flex-row">
        <!-- Sidebar -->
        <aside class="w-full sm:w-64 bg-gradient-to-b from-gray-900 to-black text-white flex-shrink-0 flex flex-col">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#4169E1] to-blue-900 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/20">
                        M
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Meta<span class="text-[#4169E1]">Portal</span></h1>
                </div>
            </div>
            
            <nav class="mt-6 px-3 space-y-1 flex-1">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Navigation</p>

                <a href="{{ auth()->user()->isSuperAdmin() ? route('admin.dashboard') : (auth()->user()->isCompanyAdmin() ? route('company.dashboard') : route('agent.dashboard')) }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('*.dashboard') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('company.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.*') && !request()->routeIs('company.users.*') && !request()->routeIs('company.settings.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Companies
                </a>

                <a href="{{ route('admin.plans.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('admin.plans.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    Plans
                </a>

                <a href="{{ route('admin.subscriptions.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('admin.subscriptions.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    Subscriptions
                </a>

                <a href="{{ route('admin.requests.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('admin.requests.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pending Requests
                </a>
                @endif
                
                @if(auth()->user()->isCompanyAdmin())
                <a href="{{ route('company.users.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.users.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Management
                </a>

                <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Configuration</p>

                <a href="{{ route('company.settings.edit') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.settings.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    Company Settings
                </a>

                <a href="{{ route('company.whatsapp.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.whatsapp.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    WhatsApp Numbers
                </a>

                <a href="{{ route('telegram.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('telegram.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Telegram Bots
                </a>

                <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Billing</p>
                
                <a href="{{ route('company.subscription.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.subscription.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    My Subscription
                </a>
                @endif

                @if(auth()->user()->isAgent())
                <a href="{{ route('agent.whatsapp.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('agent.whatsapp.*') 
                      ? 'bg-[#4169E1] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    My Numbers
                </a>
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-800">
                <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-800 transition-all duration-200 group">
                    <div class="w-9 h-9 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-300 group-hover:bg-[#4169E1] group-hover:text-white transition-colors">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-200 truncate group-hover:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate group-hover:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="font-bold text-xl text-gray-900 leading-tight">
                        {{ $header ?? 'Dashboard' }}
                    </h2>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="relative p-2 text-gray-500 hover:text-[#4169E1] hover:bg-blue-50 rounded-full transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] text-white font-bold items-center justify-center">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
                                 style="display: none;">
                                <div class="p-4 border-b border-gray-50 flex justify-between items-center">
                                    <h3 class="font-bold text-gray-900">Notifications</h3>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                    <button onclick="markAllAsRead()" class="text-xs text-[#4169E1] font-semibold hover:underline">Mark all as read</button>
                                    @endif
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ $notification->unread() ? 'bg-blue-50/30' : '' }}">
                                        <div class="flex items-start">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-800 {{ $notification->unread() ? 'font-semibold' : '' }}">
                                                    {{ $notification->data['message'] ?? 'Notification received' }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if($notification->unread())
                                            <button onclick="markAsRead('{{ $notification->id }}')" class="ml-2 w-2 h-2 bg-[#4169E1] rounded-full" title="Mark as read"></button>
                                            @endif
                                        </div>
                                        @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="mt-2 block text-xs text-[#4169E1] font-bold hover:underline">View details →</a>
                                        @endif
                                    </div>
                                    @empty
                                    <div class="p-8 text-center">
                                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="text-sm text-gray-400">All caught up!</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                            {{ auth()->user()->isSuperAdmin() ? 'bg-purple-100 text-purple-800' : (auth()->user()->isCompanyAdmin() ? 'bg-blue-100 text-[#4169E1]' : 'bg-green-100 text-green-800') }}">
                            {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : (auth()->user()->isCompanyAdmin() ? 'Company Admin' : 'Agent') }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all duration-150">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg" role="alert">
                    <svg class="w-5 h-5 mr-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif
            
            @if(session('error'))
            <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
                    <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <div class="py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
    @stack('scripts')
    <script>
        function markAsRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }).then(() => window.location.reload());
        }

        function markAllAsRead() {
            fetch(`/notifications/read-all`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }).then(() => window.location.reload());
        }
    </script>
</body>
</html>
