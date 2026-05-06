<x-company::layouts.master>
    <x-slot name="header">
        WhatsApp Chat: {{ $whatsapp->session_name }}
    </x-slot>

    <div class="h-[calc(100vh-12rem)] flex bg-white/50 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl overflow-hidden" x-data="whatsappChat()">
        <!-- Sidebar -->
        <div class="w-80 border-r border-gray-100 flex flex-col bg-white/30">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Conversations</h3>
                    <button @click="showNewChatModal = true" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>
                <div class="relative">
                    <input type="text" placeholder="Search chats..." class="w-full pl-10 pr-4 py-2 bg-gray-100/50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1" id="chats-list">
                @foreach($chats as $chat)
                <button @click="selectChat({{ json_encode($chat) }})" 
                        :class="activeChat && activeChat.id === {{ $chat->id }} ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700'"
                        class="w-full flex items-center p-4 rounded-2xl transition-all duration-200 group text-left">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/20 mr-4">
                        {{ substr($chat->name ?? $chat->chat_id, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h4 class="font-bold truncate text-sm">{{ $chat->name ?? $chat->chat_id }}</h4>
                            <span class="text-[10px] text-gray-400">{{ $chat->last_message_at ? $chat->last_message_at->format('H:i') : '' }}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate mt-0.5" id="chat-preview-{{ $chat->id }}">
                            {{ $chat->messages->first()->text ?? 'No messages yet' }}
                        </p>
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        <!-- New Chat Modal -->
        <div x-show="showNewChatModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl" @click.away="showNewChatModal = false">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">New Conversation</h3>
                <p class="text-gray-500 text-sm mb-6">Enter the phone number with country code (e.g., 201129274930).</p>
                
                <input type="text" x-model="newChatPhone" 
                       placeholder="Phone number..." 
                       class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm mb-6 focus:ring-4 focus:ring-blue-500/10 transition-all">
                
                <div class="flex space-x-3">
                    <button @click="showNewChatModal = false" class="flex-1 py-4 text-gray-600 font-bold hover:bg-gray-50 rounded-2xl transition-all">Cancel</button>
                    <button @click="startNewChat()" class="flex-1 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all">Start Chat</button>
                </div>
            </div>
        </div>

        <!-- Main Chat -->
        <div class="flex-1 flex flex-col relative bg-gradient-to-b from-transparent to-gray-50/30">
            <template x-if="activeChat">
                <div class="flex-1 flex flex-col h-full">

                    <!-- Chat Header -->
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white/50 backdrop-blur-md sticky top-0 z-10">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-4">
                                <span x-text="(activeChat.name || activeChat.chat_id).substring(0,1)"></span>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900" x-text="activeChat.name || activeChat.chat_id"></h3>
                                <p class="text-xs text-green-500 flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                    Active Now
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Window -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-window">
                        <template x-for="msg in activeChat.messages" :key="msg.id">
                            <div :class="msg.direction === 'in' ? 'flex' : 'flex flex-row-reverse'">
                                    <div :class="msg.direction === 'in' ? 'bg-white text-gray-800' : 'bg-blue-600 text-white shadow-lg shadow-blue-500/20'"
                                         class="max-w-[70%] rounded-2xl p-4 text-sm relative group">
                                        <template x-if="msg.media_path">
                                            <div class="mb-2 rounded-xl overflow-hidden">
                                                <template x-if="msg.media_type === 'image'">
                                                    <img :src="msg.media_path" class="max-w-full hover:scale-105 transition-transform duration-300">
                                                </template>
                                                <template x-if="msg.media_type === 'audio'">
                                                    <audio controls class="max-w-full">
                                                        <source :src="msg.media_path">
                                                    </audio>
                                                </template>
                                                <template x-if="msg.media_type === 'sticker'">
                                                    <img :src="msg.media_path" class="w-32 h-32 object-contain">
                                                </template>
                                            </div>
                                        </template>
                                        <p x-show="msg.media_type !== 'sticker'" x-text="msg.text" class="leading-relaxed"></p>
                                        <span class="text-[10px] mt-2 block opacity-50" x-text="formatDate(msg.created_at)"></span>

                                        <!-- Reactions -->
                                        <template x-if="msg.reactions && msg.reactions.length > 0">
                                            <div class="absolute -bottom-3 flex space-x-1" :class="msg.direction === 'in' ? 'left-2' : 'right-2'">
                                                <template x-for="reaction in msg.reactions">
                                                    <span class="bg-white rounded-full shadow-sm px-1.5 py-0.5 text-xs border border-gray-100 ring-2 ring-gray-50" x-text="reaction.text"></span>
                                                </template>
                                            </div>
                                        </template>
                                    </div>

                            </div>
                        </template>
                        <!-- Sending indicator -->
                        <template x-if="isSending">
                            <div class="flex flex-row-reverse">
                                <div class="bg-blue-400 text-white rounded-2xl p-4 text-sm opacity-70 animate-pulse">
                                    Sending...
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Input Area -->
                    <div class="p-6 bg-white/50 backdrop-blur-md border-t border-gray-100">
                        <div class="flex items-end space-x-3">
                            <div class="flex-1 relative">
                                <textarea x-model="newMessage" 
                                          @keydown.enter.prevent="sendMessage()"
                                          placeholder="Type your message..." 
                                          rows="1"
                                          class="w-full pl-6 pr-12 py-4 bg-white border border-gray-100 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all resize-none"></textarea>
                                
                                <div class="absolute right-4 bottom-4 flex items-center space-x-2">
                                    <label class="cursor-pointer text-gray-400 hover:text-blue-500 transition-colors">
                                        <input type="file" @change="handleMedia($event)" class="hidden">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </label>
                                </div>
                            </div>
                            <button @click="sendMessage()" 
                                    :disabled="(!newMessage && !mediaFile) || isSending"
                                    class="p-4 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-xl shadow-blue-500/20 transition-all transform active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                        <template x-if="mediaFile">
                            <div class="mt-3 flex items-center bg-blue-50 p-2 rounded-xl border border-blue-100">
                                <span class="text-xs text-blue-600 font-medium truncate flex-1" x-text="'File: ' + mediaFile.name"></span>
                                <button @click="mediaFile = null" class="text-blue-600 hover:text-blue-800 ml-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="!activeChat">
                <div class="flex-1 flex flex-col items-center justify-center text-center p-12">

                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Select a Conversation</h3>
                    <p class="text-gray-500 mt-2 max-w-sm">Choose a chat from the sidebar to start messaging in real-time.</p>
                </div>
            </template>


        </div>
    </div>

    @push('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const instance = window.axios || axios;
            if (instance) {
                instance.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }
        });

        function whatsappChat() {
            return {
                activeChat: null,
                newMessage: '',
                mediaFile: null,
                showNewChatModal: false,
                newChatPhone: '',
                isSending: false,
                
                selectChat(chat) {
                    // If we're already on this chat, don't reset it from the sidebar data
                    // which might be stale (missing messages sent in this session)
                    if (this.activeChat && this.activeChat.id === chat.id) {
                        return;
                    }

                    this.activeChat = chat;
                    if (this.activeChat.messages) {
                        // Reverse messages so oldest is first
                        let messages = [...this.activeChat.messages].reverse();
                        
                        // Group reactions onto their parent messages
                        const mainMessages = messages.filter(m => m.media_type !== 'reaction');
                        const reactions = messages.filter(m => m.media_type === 'reaction');
                        
                        reactions.forEach(r => {
                            const target = mainMessages.find(m => m.message_id === r.reaction_to);
                            if (target) {
                                if (!target.reactions) target.reactions = [];
                                target.reactions.push(r);
                            }
                        });
                        
                        this.activeChat.messages = mainMessages;
                    }

                    this.$nextTick(() => this.scrollToBottom());
                },

                async startNewChat() {
                    if (!this.newChatPhone) return;

                    try {
                        const response = await axios.post("{{ route('company.whatsapp.chat.start') }}", {
                            whatsapp_number_id: {{ $whatsapp->id }},
                            phone: this.newChatPhone
                        });

                        if (response.data.success) {
                            this.showNewChatModal = false;
                            this.newChatPhone = '';
                            window.location.reload(); 
                        }
                    } catch (error) {
                        alert('Error starting chat. Ensure the phone number is correct.');
                    }
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                scrollToBottom() {
                    const el = document.getElementById('messages-window');
                    if (el) el.scrollTop = el.scrollHeight;
                },

                handleMedia(e) {
                    this.mediaFile = e.target.files[0];
                },

                async sendMessage() {
                    if ((!this.newMessage && !this.mediaFile) || this.isSending) return;

                    const messageText = this.newMessage;
                    this.isSending = true;

                    const formData = new FormData();
                    formData.append('chat_id', this.activeChat.id);
                    if (messageText) formData.append('text', messageText);
                    if (this.mediaFile) formData.append('media', this.mediaFile);

                    // Clear input immediately for snappy UX
                    this.newMessage = '';
                    const mediaFileRef = this.mediaFile;
                    this.mediaFile = null;

                    try {
                        const response = await axios.post("{{ route('company.whatsapp.chat.send') }}", formData, {
                            headers: { 'Content-Type': 'multipart/form-data' }
                        });

                        if (response.data.success && response.data.message) {
                            console.log('UI: Sent message data:', response.data.message);
                            this.addMessageToUI(response.data.message);
                        }



                    } catch (error) {
                        console.error('Failed to send message:', error);
                        // Restore the message text so user can retry
                        this.newMessage = messageText;
                        this.mediaFile = mediaFileRef;
                        alert('Error sending message. Please try again.');
                    } finally {
                        this.isSending = false;
                    }
                },

                addMessageToUI(msg) {
                    if (!this.activeChat || !this.activeChat.messages) return;
                    
                    // Handle Reactions in real-time
                    if (msg.media_type === 'reaction' && msg.reaction_to) {
                        console.log('UI: Processing reaction for message ID:', msg.reaction_to, 'Emoji:', msg.text);
                        const targetMsg = this.activeChat.messages.find(m => m.message_id === msg.reaction_to);
                        if (targetMsg) {
                            if (!targetMsg.reactions) targetMsg.reactions = [];
                            if (!targetMsg.reactions.some(r => r.message_id === msg.message_id)) {
                                targetMsg.reactions.push(msg);
                            }
                            return;
                        }
                    }

                    // Duplicate check (for regular messages)
                    const exists = this.activeChat.messages.some(m => 
                        (m.id && msg.id && m.id == msg.id) || 
                        (m.message_id && msg.message_id && m.message_id == msg.message_id)
                    );
                    
                    if (!exists) {
                        console.log('UI: Adding new message to list. Type:', msg.media_type, 'Text:', msg.text);
                        this.activeChat.messages.push(msg);
                        this.$nextTick(() => this.scrollToBottom());
                    } else {
                        console.log('UI: Message already exists, skipping.');
                    }

                },


                pollInterval: null,

                isEchoConnected: false,

                startPolling() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    this.pollInterval = setInterval(async () => {
                        // Only poll if we have an active chat AND Echo is NOT connected
                        if (!this.activeChat || this.isEchoConnected) return;

                        try {
                            const response = await axios.get(`/company/whatsapp/chat/${this.activeChat.id}/messages`);
                            if (response.data.success && response.data.messages) {
                                const incomingMessages = response.data.messages.reverse(); // Reverse so oldest is first
                                let hasNew = false;
                                incomingMessages.forEach(msg => {
                                    const exists = this.activeChat.messages.some(m => m.id === msg.id);
                                    if (!exists) {
                                        this.activeChat.messages.push(msg);
                                        hasNew = true;
                                    }

                                });
                                if (hasNew) {
                                    this.$nextTick(() => this.scrollToBottom());
                                }
                            }
                        } catch (error) {
                            console.error('Polling error:', error);
                        }
                    }, 3000);
                },

                init() {
                    this.startPolling();

                    if (window.Echo) {
                        // Listen for websocket connection state to toggle polling
                        if (window.Echo.connector.pusher) {
                            window.Echo.connector.pusher.connection.bind('connected', () => {
                                this.isEchoConnected = true;
                                console.log('Echo connected — real-time active, polling disabled.');
                            });
                            window.Echo.connector.pusher.connection.bind('disconnected', () => {
                                this.isEchoConnected = false;
                                console.log('Echo disconnected — fallback polling active.');
                            });
                            window.Echo.connector.pusher.connection.bind('unavailable', () => {
                                this.isEchoConnected = false;
                            });
                        }

                        window.Echo.private("company.{{ auth()->user()->company_id }}")
                            .listen('.whatsapp.message', (e) => {
                                console.log('UI: Echo event received:', e);
                                if (e.message) {
                                    this.addMessageToUI(e.message);
                                }
                            });


                    }
                }
            }
        }
    </script>
    @endpush
</x-company::layouts.master>
