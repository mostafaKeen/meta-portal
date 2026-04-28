<x-company::layouts.master>
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

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden flex h-[750px]">
        <!-- Sidebar: Chat List -->
        <div class="w-1/3 border-r border-gray-100 flex flex-col bg-gray-50/30">
            <div class="p-6 border-b border-gray-100 bg-white">
                <div class="relative">
                    <input type="text" placeholder="Search chats..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @forelse($chats as $c)
                    <a href="{{ route('telegram.show', [$bot, $c]) }}" 
                       class="flex items-center px-6 py-5 border-b border-gray-50 hover:bg-white transition-all duration-200 group {{ isset($chat) && $chat->id === $c->id ? 'bg-white border-l-4 border-l-blue-600' : '' }}">
                        <div class="relative flex-shrink-0">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/20">
                                {{ strtoupper(substr($c->first_name ?? 'U', 0, 1)) }}
                            </div>
                            @if($c->last_message_at && $c->last_message_at->gt(now()->subMinutes(5)))
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                            @endif
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-600 transition-colors">{{ $c->first_name }} {{ $c->last_name }}</h4>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $c->last_message_at?->diffForHumans(['short' => true]) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate font-medium">
                                @php $lastMsg = $c->messages->first(); @endphp
                                @if($lastMsg)
                                    {{ $lastMsg->direction === 'out' ? 'You: ' : '' }}{{ $lastMsg->text ?? ucfirst($lastMsg->media_type) }}
                                @else
                                    No messages yet
                                @endif
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-sm text-gray-400 font-medium">No active chats found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Area: Messaging -->
        <div class="flex-1 flex flex-col bg-white relative">
            @if(isset($chat))
                <!-- Chat Header -->
                <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-white/80 backdrop-blur-md sticky top-0 z-10">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-bold">
                            {{ strtoupper(substr($chat->first_name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base font-extrabold text-gray-900 leading-tight">{{ $chat->first_name }} {{ $chat->last_name }}</h3>
                            <p class="text-xs font-bold text-green-500 uppercase tracking-widest">Active Chat</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></button>
                        <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                </div>

                <!-- Messages Content -->
                <div class="flex-1 overflow-y-auto p-8 bg-gray-50/30 custom-scrollbar" id="message-container">
                    <div class="space-y-6">
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->direction === 'out' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] {{ $msg->direction === 'out' ? 'order-1' : 'order-2' }}">
                                    <div class="relative group">
                                        <div class="px-5 py-3 rounded-2xl shadow-sm text-sm font-medium {{ $msg->direction === 'out' ? 'bg-blue-600 text-white rounded-tr-none shadow-blue-500/10' : 'bg-white text-gray-800 rounded-tl-none border border-gray-100' }}">
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
                <div class="p-6 bg-white border-t border-gray-100">
                    <form action="{{ route('telegram.send', [$bot, $chat]) }}" method="POST" enctype="multipart/form-data" class="flex items-end space-x-4">
                        @csrf
                        <div class="flex-shrink-0">
                            <label class="cursor-pointer p-4 bg-gray-50 text-gray-400 rounded-2xl hover:bg-blue-50 hover:text-blue-600 transition-all block group relative">
                                <input type="file" name="attachment" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0].name; document.getElementById('file-indicator').classList.remove('hidden')">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <span id="file-indicator" class="hidden absolute -top-1 -right-1 w-3 h-3 bg-blue-600 rounded-full border-2 border-white"></span>
                            </label>
                        </div>
                        <div class="flex-1 relative">
                            <div id="file-name" class="text-[10px] text-blue-600 font-bold mb-1 ml-2"></div>
                            <textarea name="message" rows="1" placeholder="Type your message here..." 
                                      class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all resize-none custom-scrollbar"
                                      oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>
                        <button type="submit" class="p-4 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:scale-105 active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
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
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('message-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
    @endpush
</x-company::layouts.master>
