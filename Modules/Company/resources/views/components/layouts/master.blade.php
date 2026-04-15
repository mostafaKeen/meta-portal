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
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:flex-row">
        <!-- Sidebar -->
        <aside class="w-full sm:w-64 bg-gradient-to-b from-gray-900 to-black text-white flex-shrink-0 flex flex-col">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#2563eb] to-blue-800 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/20">
                        M
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-white">Meta<span class="text-blue-400">Portal</span></h1>
                </div>
            </div>
            
            <nav class="mt-6 px-3 space-y-1 flex-1">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Navigation</p>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('company.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.*') && !request()->routeIs('company.users.*') && !request()->routeIs('company.settings.*') 
                      ? 'bg-[#2563eb] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Companies
                </a>
                @endif
                
                @if(auth()->user()->isCompanyAdmin())
                <a href="{{ route('company.users.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.users.*') 
                      ? 'bg-[#2563eb] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Management
                </a>

                <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Configuration</p>

                <a href="{{ route('company.settings.edit') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.settings.*') 
                      ? 'bg-[#2563eb] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    Company Settings
                </a>

                <a href="{{ route('company.whatsapp.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                   {{ request()->routeIs('company.whatsapp.*') 
                      ? 'bg-[#2563eb] text-white shadow-lg shadow-blue-500/25' 
                      : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    WhatsApp Numbers
                </a>
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-300">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-200 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
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
                        <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                            {{ auth()->user()->isSuperAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-[#2563eb]' }}">
                            {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Company Admin' }}
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
</body>
</html>
