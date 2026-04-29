<x-company::layouts.master>
    @php
        $isArabic = function($text) {
            return preg_match('/[\x{0600}-\x{06FF}]/u', $text);
        };
    @endphp
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ $bot->name }} <span class="text-gray-400 font-medium text-lg ml-2">Messages</span>
            </h2>
            <a href="{{ route('telegram.index') }}" class="text-sm font-bold text-blue-600 hover:underline flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Bots
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden flex flex-col md:flex-row h-[calc(100vh-180px)] min-h-[600px] md:h-[750px]">
        <!-- Sidebar: Chat List -->
        <div class="w-full md:w-1/3 border-r border-gray-100 flex-col bg-gray-50/30 {{ isset($chat) ? 'hidden md:flex' : 'flex' }}">
            <div class="p-6 border-b border-gray-100 bg-white">
                <form action="{{ request()->getRequestUri() }}" method="GET" class="relative group">
                    @if(request('search'))
                        <input type="hidden" name="chat" value="{{ $chat->id ?? '' }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search chats or messages..." 
                           class="w-full pl-10 pr-10 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all text-left dir-auto">
                    <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    @if(request('search'))
                        <a href="{{ request()->getPathInfo() }}" class="absolute right-3 top-3 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @forelse($chats as $c)
                    <div class="border-b border-gray-50 bg-white" id="chat-row-{{ $c->id }}">
                        <!-- Chat Header Link -->
                        <a href="{{ route('telegram.show', [$bot, $c]) }}?search={{ request('search') }}" 
                           class="flex items-center px-4 md:px-6 py-4 md:py-5 hover:bg-gray-50 transition-all duration-200 group {{ isset($chat) && $chat->id === $c->id && !request('msg_id') ? 'bg-blue-50/50 border-l-4 border-l-blue-600' : '' }}">
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/20">
                                    {{ mb_substr($c->first_name ?? 'U', 0, 1, 'UTF-8') }}
                                </div>
                                @if($c->last_message_at && $c->last_message_at->gt(now()->subMinutes(5)))
                                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                                @endif
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-600 transition-colors {{ $isArabic($c->first_name) ? 'text-right' : 'text-left' }}" dir="auto">
                                        {{ $c->first_name }} {{ $c->last_name }}
                                    </h4>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter ml-2">{{ $c->last_message_at?->diffForHumans(['short' => true]) }}</span>
                                </div>
                                @if(!request('search'))
                                    <p class="text-xs text-gray-500 truncate font-medium {{ $isArabic($c->messages->first()?->text) ? 'text-right' : 'text-left' }}" dir="auto">
                                        @php $lastMsg = $c->messages->first(); @endphp
                                        @if($lastMsg)
                                            {{ $lastMsg->direction === 'out' ? 'You: ' : '' }}{{ $lastMsg->text ?? ucfirst($lastMsg->media_type) }}
                                        @else
                                            No messages yet
                                        @endif
                                    </p>
                                @else
                                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">
                                        {{ $c->messages->count() }} matches
                                    </p>
                                @endif
                            </div>
                        </a>

                        <!-- Search Matches -->
                        @if(request('search'))
                            <div class="bg-gray-50/50 pb-2">
                                @foreach($c->messages as $mMatch)
                                    <a href="{{ route('telegram.show', [$bot, $c]) }}?search={{ request('search') }}&msg_id={{ $mMatch->id }}" 
                                       class="block px-6 md:px-10 py-2 hover:bg-blue-50 transition-colors group/msg {{ request('msg_id') == $mMatch->id ? 'bg-blue-100/50' : '' }}">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $mMatch->direction === 'out' ? 'bg-blue-400' : 'bg-gray-400' }}"></div>
                                            <p class="text-[11px] text-gray-600 truncate {{ $isArabic($mMatch->text) ? 'text-right' : 'text-left' }}" dir="auto">
                                                {{ $mMatch->text }}
                                            </p>
                                        </div>
                                        <div class="flex justify-between items-center mt-1">
                                            <span class="text-[9px] text-gray-400 font-bold uppercase">{{ $mMatch->created_at->format('M d, H:i') }}</span>
                                            <span class="text-[9px] text-blue-500 font-bold opacity-0 group-hover/msg:opacity-100 transition-opacity">Jump to message →</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <p class="text-sm text-gray-500 font-bold mb-1">No results found</p>
                        <p class="text-xs text-gray-400 font-medium px-4">We couldn't find any chats or messages matching "{{ request('search') }}"</p>
                        <a href="{{ request()->getPathInfo() }}" class="mt-4 inline-block text-xs font-bold text-blue-600 hover:underline">Clear Search</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Area: Messaging -->
        <div class="flex-1 flex-col bg-white relative {{ isset($chat) ? 'flex' : 'hidden md:flex' }}">
            @if(isset($chat))
                <!-- Chat Header -->
                <div class="px-4 md:px-8 py-4 md:py-5 border-b border-gray-100 flex justify-between items-center bg-white/80 backdrop-blur-md sticky top-0 z-10">
                    <div class="flex items-center">
                        <a href="{{ route('telegram.chats', $bot) }}" class="mr-4 md:hidden text-gray-400 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-bold">
                            {{ mb_substr($chat->first_name ?? 'U', 0, 1, 'UTF-8') }}
                        </div>
                        <div class="ml-3 {{ $isArabic($chat->first_name) ? 'text-right' : 'text-left' }}" dir="auto">
                            <h3 class="text-base font-extrabold text-gray-900 leading-tight">{{ $chat->first_name }} {{ $chat->last_name }}</h3>
                            <p class="text-xs font-bold text-green-500 uppercase tracking-widest">Active Chat</p>
                        </div>
                    </div>
                   
                </div>

                <!-- Messages Content -->
                <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50/30 custom-scrollbar" id="message-container">
                    <div class="space-y-4 md:space-y-6">
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->direction === 'out' ? 'justify-end' : 'justify-start' }}" id="msg-{{ $msg->id }}">
                                <div class="max-w-[85%] md:max-w-[70%] {{ $msg->direction === 'out' ? 'order-1' : 'order-2' }}">
                                    <div class="relative group">
                                        <div class="message-bubble px-4 md:px-5 py-2.5 md:py-3 rounded-2xl shadow-sm text-sm font-medium {{ $msg->direction === 'out' ? 'bg-blue-600 text-white rounded-tr-none shadow-blue-500/10' : 'bg-white text-gray-800 rounded-tl-none border border-gray-100' }} {{ $isArabic($msg->text) ? 'text-right' : 'text-left' }}" dir="auto">
                                            @if($msg->media_path)
                                                @if($msg->media_type === 'photo')
                                                    <img src="{{ Storage::url($msg->media_path) }}" class="rounded-xl mb-2 max-h-60 object-cover cursor-pointer hover:opacity-90 transition-opacity">
                                                @elseif($msg->media_type === 'voice')
                                                    <audio controls class="mb-2 max-w-full">
                                                        <source src="{{ Storage::url($msg->media_path) }}" type="audio/mpeg">
                                                    </audio>
                                                @else
                                                    <a href="{{ Storage::url($msg->media_path) }}" target="_blank" class="flex items-center p-3 bg-black/5 rounded-xl mb-2 hover:bg-black/10 transition-colors">
                                                        <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                        <span class="truncate uppercase text-[10px] font-black">Download {{ $msg->media_type }}</span>
                                                    </a>
                                                @endif
                                            @endif
                                            
                                            <p class="leading-relaxed whitespace-pre-wrap">{{ $msg->text }}</p>
                                        </div>
                                        <div class="mt-1 flex items-center {{ $msg->direction === 'out' ? 'justify-end' : 'justify-start' }}">
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $msg->created_at->format('H:i') }}</span>
                                            @if($msg->direction === 'out')
                                                <svg class="w-3 h-3 ml-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Input Area -->
                <div class="p-4 md:p-6 bg-white border-t border-gray-100">
                    <form id="chat-form" action="{{ route('telegram.send', [$bot, $chat]) }}" method="POST" enctype="multipart/form-data" class="flex items-end space-x-2 md:space-x-4">
                        @csrf
                        <div class="flex-shrink-0">
                            <label class="cursor-pointer p-3 md:p-4 bg-gray-50 text-gray-400 rounded-2xl hover:bg-blue-50 hover:text-blue-600 transition-all block group relative">
                                <input type="file" name="attachment" id="attachment" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0].name; document.getElementById('file-indicator').classList.remove('hidden')">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <span id="file-indicator" class="hidden absolute -top-1 -right-1 w-3 h-3 bg-blue-600 rounded-full border-2 border-white"></span>
                            </label>
                        </div>
                        <div class="flex-1 relative">
                            <div id="file-name" class="text-[10px] text-blue-600 font-bold mb-1 ml-2"></div>
                            <textarea name="message" id="message-input" rows="1" placeholder="Type..." 
                                      class="w-full px-4 md:px-6 py-3 md:py-4 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all resize-none custom-scrollbar dir-auto"
                                      oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>
                        <button type="submit" id="send-button" class="p-3 md:p-4 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105 active:scale-95 disabled:opacity-50">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            @else
                <!-- No Chat Selected Placeholder -->
                <div class="flex-1 flex flex-col items-center justify-center p-20 text-center">
                    <div class="w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center mb-8">
                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Select a Conversation</h3>
                    <p class="text-gray-500 max-w-sm font-medium">Choose a chat from the left sidebar to start messaging with your Telegram users.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
        
        .dir-auto {
            unicode-bidi: plaintext;
            text-align: start;
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== REAL-TIME POLLING (works over HTTPS/ngrok) =====
            @if(isset($chat))
            let lastMessageId = {{ $messages->last()?->id ?? 0 }};
            const pollUrl = "{{ route('telegram.new-messages', [$bot, $chat]) }}";
            
            function pollMessages() {
                axios.get(pollUrl + '?last_id=' + lastMessageId)
                    .then(response => {
                        const newMessages = response.data.messages;
                        if (newMessages && newMessages.length > 0) {
                            newMessages.forEach(msg => {
                                if (!document.getElementById('msg-' + msg.id)) {
                                    appendMessage(msg);
                                    updateSidebar({
                                        chat_id: "{{ $chat->id }}",
                                        direction: msg.direction,
                                        text: msg.text,
                                        media_type: msg.media_type
                                    });
                                }
                                lastMessageId = Math.max(lastMessageId, msg.id);
                            });
                        }
                    })
                    .catch(err => console.warn('Poll error:', err));
            }

            // Poll every 3 seconds
            setInterval(pollMessages, 3000);
            @endif

            // ===== AJAX Form Submission (no page reload) =====
            const chatForm = document.getElementById('chat-form');
            if (chatForm) {
                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const messageText = document.getElementById('message-input').value;
                    const sendButton = document.getElementById('send-button');
                    
                    if (!messageText.trim() && !document.getElementById('attachment').files.length) return;

                    sendButton.disabled = true;
                    
                    axios.post(this.action, formData)
                        .then(response => {
                            if (response.data.success) {
                                document.getElementById('message-input').value = '';
                                clearAttachment();
                                
                                if (!document.getElementById('msg-' + response.data.message.id)) {
                                    appendMessage(response.data.message);
                                }
                                
                                // Track this message so polling doesn't duplicate it
                                lastMessageId = Math.max(lastMessageId || 0, response.data.message.id);
                                
                                updateSidebar({
                                    chat_id: "{{ $chat->id ?? 0 }}",
                                    direction: 'out',
                                    text: response.data.message.text,
                                    media_type: response.data.message.media_type
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error sending message:', error);
                            alert('Failed to send message.');
                        })
                        .finally(() => {
                            sendButton.disabled = false;
                        });
                });
            }
        });

        function updateSidebar(data) {
            const chatList = document.querySelector('.overflow-y-auto.custom-scrollbar');
            const chatRow = document.getElementById('chat-row-' + data.chat_id);
            
            if (chatRow) {
                // Update snippet
                const snippet = chatRow.querySelector('p.text-xs');
                if (snippet) {
                    snippet.innerText = (data.direction === 'out' ? 'You: ' : '') + (data.text || data.media_type);
                    snippet.classList.remove('text-left', 'text-right');
                    snippet.classList.add(isArabic(data.text) ? 'text-right' : 'text-left');
                }
                
                // Update time
                const time = chatRow.querySelector('span.text-\\[10px\\]');
                if (time) time.innerText = 'Just now';

                // Move to top
                chatList.prepend(chatRow.closest('.border-b'));
            }
        }

        function appendMessage(data) {
            const container = document.getElementById('message-container').querySelector('.space-y-4');
            const isOut = data.direction === 'out';
            
            const msgHtml = `
                <div class="flex ${isOut ? 'justify-end' : 'justify-start'} animate-fade-in-up" id="msg-${data.id}">
                    <div class="max-w-[85%] md:max-w-[70%] ${isOut ? 'order-1' : 'order-2'}">
                        <div class="relative group">
                            <div class="message-bubble px-4 md:px-5 py-2.5 md:py-3 rounded-2xl shadow-sm text-sm font-medium ${isOut ? 'bg-blue-600 text-white rounded-tr-none shadow-blue-500/10' : 'bg-white text-gray-800 rounded-tl-none border border-gray-100'} ${isArabic(data.text) ? 'text-right' : 'text-left'}" dir="auto">
                                ${data.media_path ? renderMedia(data) : ''}
                                <p class="leading-relaxed whitespace-pre-wrap">${data.text || ''}</p>
                            </div>
                            <div class="flex items-center mt-1.5 space-x-2 ${isOut ? 'justify-end' : 'justify-start'}">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${data.time}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', msgHtml);
            
            const scrollContainer = document.getElementById('message-container');
            scrollContainer.scrollTo({ top: scrollContainer.scrollHeight, behavior: 'smooth' });
        }

        function renderMedia(data) {
            if (data.media_type === 'photo') {
                return `<img src="${data.media_path}" class="rounded-xl mb-2 max-h-60 object-cover">`;
            } else if (data.media_type === 'voice') {
                return `<audio controls class="mb-2 max-w-full"><source src="${data.media_path}" type="audio/mpeg"></audio>`;
            }
            return `<a href="${data.media_path}" target="_blank" class="flex items-center p-3 bg-black/5 rounded-xl mb-2">
                        <span class="truncate uppercase text-[10px] font-black">Download ${data.media_type}</span>
                    </a>`;
        }

        function isArabic(text) {
            if (!text) return false;
            return /[\u0600-\u06FF]/.test(text);
        }

        function clearAttachment() {
            const attachment = document.getElementById('attachment');
            if (attachment) attachment.value = '';
            
            const fileName = document.getElementById('file-name');
            if (fileName) fileName.innerText = '';
            
            const fileIndicator = document.getElementById('file-indicator');
            if (fileIndicator) fileIndicator.classList.add('hidden');
        }

        window.onload = function() {
            const container = document.getElementById('message-container');
            const msgId = "{{ request('msg_id') }}";
            
            if (msgId) {
                const msgElement = document.getElementById('msg-' + msgId);
                if (msgElement) {
                    msgElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    const bubble = msgElement.querySelector('.message-bubble');
                    bubble.classList.add('ring-4', 'ring-yellow-400', 'ring-offset-2');
                    setTimeout(() => {
                        bubble.classList.remove('ring-4', 'ring-yellow-400', 'ring-offset-2');
                        bubble.classList.add('transition-all', 'duration-1000');
                    }, 2000);
                }
            } else if (container) {
                container.scrollTop = container.scrollHeight;
            }
        };
    </script>
    @endpush
</x-company::layouts.master>
