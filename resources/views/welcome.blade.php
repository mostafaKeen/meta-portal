<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Meta Portal') }} - Ultimate Integration Platform</title>

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
</head>
<body class="antialiased text-slate-800 selection:bg-[#2563eb] selection:text-white overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#2563eb] to-blue-800 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/30">
                        M
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">Meta<span class="text-[#2563eb]">Portal</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#services" class="text-sm font-medium text-slate-600 hover:text-[#2563eb] transition-colors">Services</a>
                    <a href="#features" class="text-sm font-medium text-slate-600 hover:text-[#2563eb] transition-colors">Features</a>
                    
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4 pl-4 border-l border-gray-200">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-[#2563eb] text-white font-medium text-sm rounded-lg hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-[#2563eb] text-white font-medium text-sm rounded-lg hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200">
                                    Sign in
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-24 lg:pt-48 lg:pb-32 overflow-hidden hero-gradient">
        <!-- Abstract Shapes -->
        <div class="absolute top-20 right-0 -mr-20 lg:-mr-40 opacity-40 mix-blend-multiply filter blur-3xl rounded-full w-96 h-96 bg-blue-200 floating-blob" style="animation-delay: 0s;"></div>
        <div class="absolute top-40 left-0 -ml-20 lg:-ml-40 opacity-40 mix-blend-multiply filter blur-3xl rounded-full w-72 h-72 bg-indigo-200 floating-blob" style="animation-delay: 2s;"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center space-x-2 bg-blue-50 border border-blue-100 rounded-full px-4 py-1.5 mb-8 shadow-sm">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#2563eb]"></span>
                </span>
                <span class="text-sm font-semibold text-[#2563eb] uppercase tracking-wide">Enterprise Integrated Solutions</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-8">
                Seamless <span class="text-gradient">Integrations</span> <br class="hidden md:block"/> for Modern Businesses
            </h1>
            
            <p class="mt-4 text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                Empower your communication and tracking workflows. MetaPortal provides a robust, multi-tenant platform for managing WhatsApp integrations and Meta Conversions APIs across your entire organization.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-[#2563eb] text-white font-semibold text-lg rounded-xl hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-200 transform hover:-translate-y-1">
                    Get Started Now
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div id="services" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-base text-[#2563eb] font-semibold tracking-wide uppercase">Core Capabilities</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl text-gradient">
                    Everything you need to connect.
                </p>
                <p class="mt-4 max-w-2xl text-xl text-slate-500 mx-auto">
                    Manage channels, automate workflows, and track conversions seamlessly under one unified dashboard.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1: WhatsApp API -->
                <div class="bg-white rounded-2xl border border-gray-100 p-8 card-hover shadow-sm flex flex-col items-start relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <svg class="w-24 h-24 text-[#2563eb]" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/></svg>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-blue-100 text-[#2563eb] flex items-center justify-center mb-6 shadow-inner z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 z-10">WhatsApp Official API</h3>
                    <p class="text-slate-600 leading-relaxed mb-4 flex-grow z-10">
                        Connect natively via Meta's Cloud API. High-throughput, verified business messaging for notifications, service updates, and conversational flows seamlessly routed to Bitrix24 open channels.
                    </p>
                    <ul class="text-sm text-slate-500 space-y-2 mb-6 z-10">
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Reliable, official infrastructure</li>
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Multi-agent support in Bitrix24</li>
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Interactive template messaging</li>
                    </ul>
                </div>

                <!-- Service 2: WhatsApp QR -->
                <div class="bg-white rounded-2xl border border-gray-100 p-8 card-hover shadow-sm flex flex-col items-start relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <svg class="w-24 h-24 text-[#2563eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-blue-100 text-[#2563eb] flex items-center justify-center mb-6 shadow-inner z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 z-10">WhatsApp Web (QR)</h3>
                    <p class="text-slate-600 leading-relaxed mb-4 flex-grow z-10">
                        Link your existing mobile number instantly via QR code. Perfect for agile teams wanting to bridge personal numbers into the CRM without complex official approval processes.
                    </p>
                    <ul class="text-sm text-slate-500 space-y-2 mb-6 z-10">
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Zero approval waiting time</li>
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Use existing phone numbers</li>
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Deep sync with Bitrix24 chats</li>
                    </ul>
                </div>

                <!-- Service 3: Meta Conversions -->
                <div class="bg-white rounded-2xl border border-gray-100 p-8 card-hover shadow-sm flex flex-col items-start relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <svg class="w-24 h-24 text-[#2563eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-blue-100 text-[#2563eb] flex items-center justify-center mb-6 shadow-inner z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 z-10">Meta Conversions API</h3>
                    <p class="text-slate-600 leading-relaxed mb-4 flex-grow z-10">
                        Push highly accurate offline conversion data—like closed deals or paid invoices—directly from Bitrix24 back to Meta Ads to optimize your ad spend and decrease CPA.
                    </p>
                    <ul class="text-sm text-slate-500 space-y-2 mb-6 z-10">
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Server-side tracking precision</li>
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Bypass cookie limitations</li>
                        <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Maximize ROI on ad campaigns</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gray-900 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <svg class="h-full w-full" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="url(#grad2)"></path>
                <defs>
                    <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#1e3a8a;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center border-t border-b border-gray-800 py-16">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl mb-4">
                Ready to elevate your operations?
            </h2>
            <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                Join our platform to centrally manage your team, securely handle API keys, and streamline your entire digital infrastructure.
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 bg-[#2563eb] border border-transparent rounded-lg font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-[#2563eb] transition-all duration-200">
                Access Portal
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12 text-center text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 rounded bg-gradient-to-br from-[#2563eb] to-blue-800 flex items-center justify-center text-white font-bold text-xs">
                        M
                    </div>
                    <span class="font-bold tracking-tight text-slate-900">Meta<span class="text-[#2563eb]">Portal</span></span>
                </div>
                <p>&copy; {{ date('Y') }} MetaPortal. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
