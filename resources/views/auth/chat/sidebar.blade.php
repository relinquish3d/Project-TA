<!-- Backdrop Overlay Gelap -->
<div id="chatSidebarBackdrop" 
     onclick="closeChatSidebar()" 
     class="{{ isset($openChat) && $openChat ? '' : 'hidden' }} fixed inset-0 bg-black/40 backdrop-blur-[1.5px] cursor-pointer transition-opacity z-50"
     title="Klik untuk menutup chat sidebar dan kembali ke Dashboard">
</div>

<!-- ================= CHAT SIDEBAR DRAWER KANAN (WARNA SESUAI PROFILE) ================= -->
<aside id="chatSidebar" 
       class="{{ isset($openChat) && $openChat ? 'translate-x-0' : 'translate-x-full' }} fixed top-0 right-0 h-screen w-full sm:w-[680px] md:w-[760px] lg:w-[820px] bg-[#FDFCF8] shadow-2xl z-50 flex flex-row overflow-hidden border-l border-gray-300 transition-transform duration-300 ease-in-out selection:bg-gray-300 selection:text-black">
    
    <!-- KOLOM KIRI SIDEBAR: Daftar Kontak Chat -->
    <div class="w-64 sm:w-72 md:w-80 border-r border-gray-300 bg-[#D9D9D9] flex flex-col h-full shrink-0">
        <!-- Header Sidebar: ✕ Chat -->
        <div class="p-4 flex items-center justify-between border-b border-gray-300 bg-[#D9D9D9]">
            <div class="flex items-center gap-3">
                <button type="button" 
                        onclick="closeChatSidebar()" 
                        class="p-1 rounded-lg text-black hover:bg-gray-300 transition cursor-pointer"
                        title="Tutup Chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h2 class="text-xl font-bold italic text-black tracking-tight">Chat</h2>
            </div>
        </div>

        <!-- List Kontak Chat -->
        <div class="overflow-y-auto flex-1 divide-y divide-gray-300/80 bg-[#D9D9D9]">
            @if(isset($users))
                @forelse($users as $u)
                    <a href="{{ route('chat.show', $u->id) }}" 
                       class="flex items-center justify-between p-3.5 hover:bg-[#C4C4C4]/60 transition cursor-pointer block {{ isset($activeUser) && $activeUser->id == $u->id ? 'bg-[#C4C4C4]' : '' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Avatar -->
                            <div class="relative shrink-0">
                                <img src="{{ $u->avatar_url }}" 
                                     alt="{{ $u->name }}" 
                                     class="w-11 h-11 rounded-full object-cover border-2 border-gray-300 shadow-xs">
                            </div>
                            <div class="min-w-0 truncate">
                                <h3 class="font-bold text-black text-sm truncate leading-tight">{{ $u->name }}</h3>
                                <p id="contact-last-msg-{{ $u->id }}" class="text-xs text-gray-700 truncate max-w-[130px] mt-0.5">
                                    {{ $u->last_message ? $u->last_message->message : 'Belum ada pesan' }}
                                </p>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-600 font-medium shrink-0 self-start mt-0.5">
                            {{ $u->last_message?->created_at ? $u->last_message->created_at->format('n-j H:i') : '' }}
                        </span>
                    </a>
                @empty
                    <div class="p-6 text-center text-gray-600 text-xs italic">
                        Belum ada pengguna lain.
                    </div>
                @endforelse
            @endif
        </div>
    </div>

    <!-- KOLOM KANAN SIDEBAR: Ruang Percakapan Chat Aktif -->
    <div class="flex-1 bg-[#FDFCF8] flex flex-col h-full min-w-0">
        @if(isset($activeUser) && $activeUser)
            <!-- Header Chat Aktif -->
            <div class="px-5 py-3.5 border-b border-gray-300 flex items-center justify-between bg-[#D9D9D9] shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="relative shrink-0">
                        <img src="{{ $activeUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($activeUser->name) }}" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-300 shadow-xs">
                    </div>
                    <div>
                        <span class="font-bold italic text-black text-sm md:text-base leading-tight">{{ $activeUser->name }}</span>
                    </div>
                </div>

                <!-- Menu Opsi -->
                <div class="flex items-center gap-2">
                    <button type="button" class="text-black hover:opacity-75 p-1 rounded-full transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Isi Gelembung Pesan -->
            <div id="messages-container" class="flex-1 p-5 overflow-y-auto space-y-3.5 bg-[#FDFCF8]">
                <!-- Divider Tanggal -->
                <div class="text-center my-2">
                    <span class="text-[11px] text-gray-700 font-medium bg-[#D9D9D9] border border-gray-300 px-3 py-1 rounded-full shadow-2xs">
                        {{ date('n-j H:i') }}
                    </span>
                </div>

                @if(isset($messages))
                    @forelse($messages as $msg)
                        @if($msg->sender_id == auth()->id())
                            <!-- Pesan Pengirim (User Login - Kanan: Abu-abu Gelap Khas Kotak Form) -->
                            <div class="flex items-end justify-end space-x-2 my-2">
                                <div class="flex flex-col items-end max-w-[80%]">
                                    <div class="bg-[#C4C4C4] border border-gray-400 text-black px-4 py-2.5 rounded-2xl rounded-tr-xs text-sm shadow-xs break-words leading-relaxed font-normal">
                                        {{ $msg->message }}
                                    </div>
                                    <span class="text-[10px] text-gray-500 mt-1 mr-1">
                                        {{ $msg->created_at ? $msg->created_at->format('H:i') : '' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <!-- Pesan Penerima (Lawan Bicara - Kiri: Putih Border) -->
                            <div class="flex items-start space-x-2 my-2">
                                <img src="{{ $activeUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($activeUser->name) }}" 
                                     class="w-8 h-8 rounded-full object-cover shrink-0 mt-0.5 border border-gray-300">
                                <div class="flex flex-col items-start max-w-[80%]">
                                    <div class="bg-white border border-gray-300 text-black px-4 py-2.5 rounded-2xl rounded-tl-xs text-sm shadow-xs break-words leading-relaxed">
                                        {{ $msg->message }}
                                    </div>
                                    <span class="text-[10px] text-gray-500 mt-1 ml-1">
                                        {{ $msg->created_at ? $msg->created_at->format('H:i') : '' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div id="empty-chat-message" class="text-center text-gray-500 text-xs my-auto py-10 italic">
                            Belum ada pesan. Mulai sapa {{ $activeUser->name }}!
                        </div>
                    @endforelse
                @endif

                <!-- Indikator Typing -->
                <div id="typing-indicator" class="hidden flex items-start space-x-2 pt-1">
                    <img src="{{ $activeUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($activeUser->name) }}" 
                         class="w-7 h-7 rounded-full object-cover border border-gray-300">
                    <div class="bg-white border border-gray-300 px-3 py-1.5 rounded-full shadow-xs flex items-center space-x-1">
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                    </div>
                </div>
            </div>

            <!-- Form Input Kirim Pesan -->
            <div class="p-3.5 px-4 bg-[#D9D9D9] border-t border-gray-300">
                <form id="chat-form" action="{{ route('chat.send', $activeUser->id) }}" method="POST" class="relative flex items-center gap-2">
                    @csrf
                    <input type="text" id="message-input" name="message" placeholder="Isi disini..." required autocomplete="off"
                           class="flex-1 bg-white border border-gray-400 text-black text-sm rounded-full py-2.5 pl-5 pr-12 focus:outline-none focus:border-black shadow-inner placeholder-gray-500 transition">
                    
                    <button type="submit" 
                            class="w-9 h-9 rounded-full bg-black hover:bg-gray-800 text-white flex items-center justify-center transition shadow-md cursor-pointer shrink-0"
                            title="Kirim">
                        <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <!-- Tampilan jika tidak ada user terpilih -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-gray-500 bg-[#FDFCF8]">
                <div class="w-16 h-16 rounded-full bg-[#D9D9D9] text-black flex items-center justify-center mb-4 border border-gray-300 shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold italic text-black mb-1">Pilih Kontak</h3>
                <p class="text-xs text-gray-600 max-w-xs">
                    Pilih salah satu teman mabar di daftar samping untuk mulai mengobrol.
                </p>
            </div>
        @endif
    </div>

</aside>

<style>
    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-5px); }
    }
    .typing-dot {
        width: 6px;
        height: 6px;
        background-color: #6B7280;
        border-radius: 9999px;
        display: inline-block;
        animation: typingBounce 1.2s infinite ease-in-out;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    .typing-dot:nth-child(3) { animation-delay: 0s; }
</style>

<!-- Script Pusher & Interaktivitas Chat -->
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script id="chat-config" type="application/json">
    {!! json_encode([
        'pusherKey' => env('PUSHER_APP_KEY'),
        'pusherCluster' => env('PUSHER_APP_CLUSTER', 'ap1'),
        'authId' => auth()->id(),
        'activeUser' => isset($activeUser) && $activeUser ? [
            'id' => $activeUser->id,
            'name' => $activeUser->name,
            'avatar' => $activeUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($activeUser->name),
            'typingUrl' => route('chat.typing', $activeUser->id),
            'sendUrl' => route('chat.send', $activeUser->id),
        ] : null,
        'senderAvatar' => auth()->user()?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()?->name ?? 'User'),
        'csrfToken' => csrf_token(),
    ]) !!}
</script>
<script>
    function openChatSidebar() {
        const backdrop = document.getElementById('chatSidebarBackdrop');
        const sidebar = document.getElementById('chatSidebar');
        if (backdrop && sidebar) {
            backdrop.classList.remove('hidden');
            sidebar.classList.remove('translate-x-full');
            document.body.classList.add('overflow-hidden');
            
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
    }

    function closeChatSidebar() {
        const backdrop = document.getElementById('chatSidebarBackdrop');
        const sidebar = document.getElementById('chatSidebar');
        if (backdrop && sidebar) {
            backdrop.classList.add('hidden');
            sidebar.classList.add('translate-x-full');
            document.body.classList.remove('overflow-hidden');
        }

        // Jika URL saat ini adalah /chat atau memiliki parameter chat, bersihkan URL atau kembali ke dashboard
        if (window.location.pathname.includes('/chat')) {
            window.location.href = "{{ route('dashboard') }}";
        } else if (window.location.search.includes('chat')) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }

    // Tekan tombol ESC untuk menutup chat sidebar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('chatSidebar');
            if (sidebar && !sidebar.classList.contains('translate-x-full')) {
                closeChatSidebar();
            }
        }
    });

    // Auto-scroll ke bawah saat sidebar terbuka
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Inisialisasi Pusher jika ada
    const chatConfig = JSON.parse(document.getElementById('chat-config').textContent);

    function updateSidebarLastMsg(userId, text) {
        const el = document.getElementById('contact-last-msg-' + userId);
        if (el) el.textContent = text;
    }

    if (chatConfig.pusherKey && typeof Pusher !== 'undefined') {
        try {
            const pusher = new Pusher(chatConfig.pusherKey, { cluster: chatConfig.pusherCluster });
            const userChannel = pusher.subscribe('chat.' + chatConfig.authId);

            if (chatConfig.activeUser) {
                const activeUserId = chatConfig.activeUser.id;
                const senderAvatar = chatConfig.senderAvatar;
                const receiverAvatar = chatConfig.activeUser.avatar;
                const typingIndicator = document.getElementById('typing-indicator');
                let hideTypingTimeout = null;

                function appendMessage(text, isSender, timeStr = null) {
                    const emptyHint = document.getElementById('empty-chat-message');
                    if (emptyHint) emptyHint.remove();

                    const now = timeStr || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    const row = document.createElement('div');

                    if (isSender) {
                        row.className = 'flex items-end justify-end space-x-2 my-2';
                        row.innerHTML = `
                            <div class="flex flex-col items-end max-w-[80%]">
                                <div class="bg-[#C4C4C4] border border-gray-400 text-black px-4 py-2.5 rounded-2xl rounded-tr-xs text-sm shadow-xs break-words leading-relaxed font-normal">
                                    ${escapeHtml(text)}
                                </div>
                                <span class="text-[10px] text-gray-500 mt-1 mr-1">${now}</span>
                            </div>
                        `;
                    } else {
                        row.className = 'flex items-start space-x-2 my-2';
                        row.innerHTML = `
                            <img src="${receiverAvatar}" class="w-8 h-8 rounded-full object-cover shrink-0 mt-0.5 border border-gray-300">
                            <div class="flex flex-col items-start max-w-[80%]">
                                <div class="bg-white border border-gray-300 text-black px-4 py-2.5 rounded-2xl rounded-tl-xs text-sm shadow-xs break-words leading-relaxed">
                                    ${escapeHtml(text)}
                                </div>
                                <span class="text-[10px] text-gray-500 mt-1 ml-1">${now}</span>
                            </div>
                        `;
                    }

                    if (typingIndicator) {
                        messagesContainer.insertBefore(row, typingIndicator);
                    } else {
                        messagesContainer.appendChild(row);
                    }
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }

                userChannel.bind('new-message', function(data) {
                    updateSidebarLastMsg(data.sender_id, data.message);
                    if (data.sender_id == activeUserId) {
                        if (typingIndicator) typingIndicator.classList.add('hidden');
                        appendMessage(data.message, false);
                    }
                });

                userChannel.bind('typing', function(data) {
                    if (data.from_id == activeUserId && typingIndicator) {
                        typingIndicator.classList.remove('hidden');
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;

                        clearTimeout(hideTypingTimeout);
                        hideTypingTimeout = setTimeout(() => {
                            typingIndicator.classList.add('hidden');
                        }, 2500);
                    }
                });

                const messageInput = document.getElementById('message-input');
                let lastTypingTime = 0;
                if (messageInput) {
                    messageInput.addEventListener('input', function() {
                        const now = Date.now();
                        if (now - lastTypingTime > 1500) {
                            lastTypingTime = now;
                            fetch(chatConfig.activeUser.typingUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': chatConfig.csrfToken,
                                    'Content-Type': 'application/json'
                                }
                            }).catch(() => {});
                        }
                    });
                }

                const chatForm = document.getElementById('chat-form');
                if (chatForm && messageInput) {
                    chatForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const text = messageInput.value.trim();
                        if (!text) return;

                        messageInput.value = '';
                        appendMessage(text, true);
                        updateSidebarLastMsg(activeUserId, text);

                        fetch(chatConfig.activeUser.sendUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': chatConfig.csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ message: text })
                        }).catch(err => {
                            console.error('Send failed:', err);
                        });
                    });
                }
            } else {
                userChannel.bind('new-message', function(data) {
                    updateSidebarLastMsg(data.sender_id, data.message);
                });
            }
        } catch (err) {
            console.warn('Pusher error:', err);
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, "&amp;")
                   .replace(/</g, "&lt;")
                   .replace(/>/g, "&gt;")
                   .replace(/"/g, "&quot;")
                   .replace(/'/g, "&#039;");
    }
</script>
