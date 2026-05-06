<x-company::layouts.master>
    <x-slot name="header">
        WhatsApp Numbers
    </x-slot>

    <!-- Header Card -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manage WhatsApp Numbers</h2>
            <p class="text-sm text-gray-500 mt-1">Configure API and QR-based WhatsApp connections for your company.</p>
            @php
                $activePlan = auth()->user()->company->activePlan();
            @endphp
            @if($activePlan)
                <p class="text-xs font-semibold {{ auth()->user()->company->hasReachedWhatsappLimit() ? 'text-red-500' : 'text-gray-400' }} mt-1">
                    Usage: {{ auth()->user()->company->whatsappNumbers()->where('type', 'qr')->count() }} / {{ $activePlan->max_qr_numbers == -1 ? 'Unlimited' : $activePlan->max_qr_numbers }} WhatsApp QR Numbers
                </p>
            @endif
        </div>
        <a href="{{ route('company.whatsapp.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#2563eb] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Number
        </a>
    </div>

    @if($numbers->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($numbers as $number)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:border-gray-200 transition-all duration-200">
            <!-- Card Header -->
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between {{ $number->isApi() ? 'bg-gradient-to-r from-indigo-50 to-blue-50' : 'bg-gradient-to-r from-green-50 to-emerald-50' }}">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ $number->isApi() ? 'bg-indigo-100 text-indigo-600' : 'bg-green-100 text-green-600' }}">
                        @if($number->isApi())
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider {{ $number->isApi() ? 'text-indigo-600' : 'text-green-600' }}">
                            {{ $number->type }}
                        </span>
                        <p class="text-sm font-bold text-gray-900">{{ $number->isApi() ? $number->app_name : $number->session_name }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $number->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($number->status) }}
                </span>
            </div>

            <!-- Card Body -->
            <div class="px-5 py-4 space-y-3">
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 text-gray-400 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span class="font-mono font-medium text-gray-800">{{ $number->phone_number }}</span>
                </div>

                @if($number->isApi())
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 text-gray-400 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <span class="text-gray-600 truncate">ID: {{ $number->app_id }}</span>
                </div>
                @else
                    @if($number->status !== 'active')
                    <div id="qr-container-{{ $number->id }}" class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col items-center">
                        <div id="qr-image-{{ $number->id }}" class="w-48 h-48 bg-white rounded-lg shadow-inner flex items-center justify-center overflow-hidden">
                            {{-- QR will be rendered here by JavaScript --}}
                            @if(!$number->qr_code)
                                <div class="text-center p-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-2"></div>
                                    <p class="text-xs text-gray-400">Waiting for QR...</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-[10px] text-center text-gray-400 mt-2">Scan with WhatsApp to link account</p>
                        <button onclick="retryConnection({{ $number->id }}, '{{ $number->session_name }}')" class="mt-2 text-[10px] text-blue-500 hover:underline">Retry Connection</button>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Card Actions -->
            <div class="px-5 py-3 border-t border-gray-50 bg-gray-50/50 flex items-center justify-end space-x-2">
                @if($number->status === 'active')
                <a href="{{ route('company.whatsapp.show', $number) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-[#2563eb] rounded-lg hover:bg-blue-700 transition-all">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    View
                </a>
                @endif
                <a href="{{ route('company.whatsapp.edit', $number) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:text-[#2563eb] hover:border-blue-200 hover:bg-blue-50 transition-all">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <form action="{{ route('company.whatsapp.destroy', $number) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this number?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">No WhatsApp numbers configured</h3>
        <p class="text-sm text-gray-500 mb-6">Get started by adding your first API or QR-based WhatsApp connection.</p>
        <a href="{{ route('company.whatsapp.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#2563eb] text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add First Number
        </a>
    </div>
    @endif
    @push('scripts')
    <!-- CSRF Token for axios -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Client-side QR code library -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script id="qr-lib" src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script>
        // ── Axios CSRF setup ──────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
        });

        // ── QR Rendering ──────────────────────────────────────────────────────
        function renderQR(containerId, data) {
            const container = document.getElementById(containerId);
            if (!container || !data) return;
            
            if (typeof QRCode === 'undefined') {
                // Library not loaded yet — retry in 500ms
                setTimeout(() => renderQR(containerId, data), 500);
                return;
            }

            QRCode.toDataURL(data, {
                width: 192,
                margin: 1,
                color: { dark: '#000000', light: '#ffffff' }
            }, function(error, url) {
                if (error) {
                    console.error('QR render error:', error);
                    return;
                }
                container.innerHTML = `<img src="${url}" class="w-full h-full object-contain" alt="WhatsApp QR Code">`;
            });
        }

        // ── Initialize QR codes once library is loaded ────────────────────────
        function initializeQRCodes() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeQRCodes);
                return;
            }

            if (typeof QRCode === 'undefined') {
                setTimeout(initializeQRCodes, 200);
                return;
            }

            @foreach($numbers as $number)
                @if($number->qr_code && $number->status !== 'active')
                    renderQR('qr-image-{{ $number->id }}', @json($number->qr_code));
                @endif
            @endforeach
        }

        // Trigger when QR library finishes loading
        document.getElementById('qr-lib').onload = initializeQRCodes;

        // ── Echo real-time listeners ──────────────────────────────────────────
        let echoConnected = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Also try in case library was cached
            if (typeof QRCode !== 'undefined') initializeQRCodes();

            if (window.Echo) {
                if (window.Echo.connector && window.Echo.connector.pusher) {
                    window.Echo.connector.pusher.connection.bind('connected', () => { echoConnected = true; });
                    window.Echo.connector.pusher.connection.bind('disconnected', () => { echoConnected = false; });
                    window.Echo.connector.pusher.connection.bind('unavailable', () => { echoConnected = false; });
                }

                window.Echo.private("company.{{ auth()->user()->company_id }}")
                    .listen('.whatsapp.qr', (e) => {
                        console.log('QR Code Received via Echo:', e);
                        renderQR(`qr-image-${e.number.id}`, e.qr);
                    })
                    .listen('.whatsapp.message', (e) => {
                        console.log('WhatsApp Message Received:', e);
                    })
                    .listen('.whatsapp.status', (e) => {
                        console.log('WhatsApp Status Updated:', e);
                        if (e.status === 'active') {
                            window.location.reload();
                        }
                    });
            }

            // ── QR Polling fallback (every 5s when Echo is down) ──────────────
            startQRPolling();
        });

        // ── QR Polling ────────────────────────────────────────────────────────
        @php
            $qrNumberIds = $numbers->where('type', 'qr')->where('status', '!=', 'active')->pluck('id')->values();
        @endphp
        const qrNumbers = @json($qrNumberIds);
        let lastKnownQR = {};

        function startQRPolling() {
            if (qrNumbers.length === 0) return;

            setInterval(async () => {
                // Skip polling if Echo is working
                if (echoConnected) return;

                for (const numberId of qrNumbers) {
                    try {
                        const response = await axios.get(`/company/whatsapp/${numberId}/qr-status`);
                        const { qr_code, status } = response.data;

                        if (status === 'active') {
                            window.location.reload();
                            return;
                        }

                        if (qr_code && qr_code !== lastKnownQR[numberId]) {
                            lastKnownQR[numberId] = qr_code;
                            renderQR(`qr-image-${numberId}`, qr_code);
                        }
                    } catch (e) {
                        console.warn(`QR poll error for ${numberId}:`, e);
                    }
                }
            }, 5000);
        }

        // ── Retry Connection ──────────────────────────────────────────────────
        function retryConnection(id, sessionName) {
            const container = document.getElementById(`qr-image-${id}`);
            container.innerHTML = `
                <div class="text-center p-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-2"></div>
                    <p class="text-xs text-gray-400">Restarting session...</p>
                </div>
            `;

            axios.post("{{ route('whatsapp.session.retry') }}", {
                session_id: sessionName
            })
            .then(response => {
                console.log('Session restart triggered');
            })
            .catch(error => {
                console.error('Failed to restart session:', error);
                container.innerHTML = `<p class="text-xs text-red-500 text-center p-4">Failed to restart. Try again.</p>`;
            });
        }
    </script>
    @endpush
</x-company::layouts.master>
