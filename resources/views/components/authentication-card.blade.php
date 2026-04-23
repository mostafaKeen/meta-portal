<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-900 via-gray-950 to-black relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-[#4169E1]/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-[#4169E1]/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Logo -->
    <div class="relative z-10 mb-8">
        {{ $logo }}
    </div>

    <!-- Card -->
    <div class="relative z-10 w-full sm:max-w-md px-8 py-8 bg-white/[0.03] backdrop-blur-xl border border-white/10 shadow-2xl shadow-black/30 rounded-3xl">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <p class="relative z-10 mt-8 text-xs text-gray-600">&copy; {{ date('Y') }} MetaPortal. All rights reserved.</p>
</div>
