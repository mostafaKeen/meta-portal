<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Meta Portal') }} - Ultimate Integration Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        }

        .text-gradient {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.1), 0 10px 10px -5px rgba(37, 99, 235, 0.04);
            border-color: #bfdbfe;
        }
        
        .floating-blob {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
            100% { transform: translateY(0px) scale(1); }
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-slate-800 selection:bg-[#2563eb] selection:text-white overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#2563eb] to-blue-800 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/30">
                        M
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">Meta<span class="text-[#4169E1]">Portal</span></span>
                </a>
 
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-sm font-medium text-slate-600 hover:text-[#4169E1] transition-colors">Home</a>
                    <a href="{{ route('plans.public') }}" class="text-sm font-medium text-slate-600 hover:text-[#4169E1] transition-colors">Plans</a>
                    <a href="/#features" class="text-sm font-medium text-slate-600 hover:text-[#4169E1] transition-colors">Features</a>
                    
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4 pl-4 border-l border-gray-200">
                            @auth
                                @php
                                    $dashboardRoute = auth()->user()->isSuperAdmin() ? 'admin.dashboard' : (auth()->user()->isCompanyAdmin() ? 'company.dashboard' : 'agent.dashboard');
                                @endphp
                                <a href="{{ route($dashboardRoute) }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-[#4169E1] text-white font-medium text-sm rounded-lg hover:bg-blue-800 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-[#4169E1] text-white font-medium text-sm rounded-lg hover:bg-blue-800 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200">
                                    Sign in
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12 text-center text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 rounded bg-gradient-to-br from-[#4169E1] to-blue-800 flex items-center justify-center text-white font-bold text-xs">
                        M
                    </div>
                    <span class="font-bold tracking-tight text-slate-900">Meta<span class="text-[#4169E1]">Portal</span></span>
                </div>
                <p>&copy; {{ date('Y') }} MetaPortal. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
