<x-whatsappqr::layouts.master>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-10 lg:p-12 w-full max-w-4xl mx-auto flex flex-col items-center justify-center text-center">
        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 tracking-tight">Welcome to WhatsAppQR</h1>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto mb-8 leading-relaxed">
            Module: <span class="font-semibold text-blue-600 px-3 py-1 bg-blue-50 rounded-full">{!! config('whatsappqr.name') !!}</span>
        </p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full mt-4">
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 hover:border-blue-200 transition-colors">
                <h3 class="font-bold text-gray-900 mb-2">Scan & Connect</h3>
                <p class="text-sm text-gray-500">Link your WhatsApp account via QR code to start receiving and sending messages instantly.</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 hover:border-blue-200 transition-colors">
                <h3 class="font-bold text-gray-900 mb-2">Real-Time Messaging</h3>
                <p class="text-sm text-gray-500">Enjoy seamless real-time communication powered by Laravel Echo and reliable background polling.</p>
            </div>
        </div>
    </div>
</x-whatsappqr::layouts.master>
