<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col font-sans">

    <!-- Container Utama UI Chat -->
    <div class="flex h-full w-full bg-white overflow-hidden">
        
        <!-- SIDEBAR KIRI: Daftar User -->
        <div class="w-1/3 border-r border-gray-200 bg-[#D9D9D9] flex flex-col">
            <!-- Header Sidebar -->
            <div class="p-4 flex items-center space-x-3 border-b border-gray-300">
                <!-- Tombol Kembali ke Landing Page -->
                <a href="{{ url('/') }}" class="text-black font-bold hover:opacity-70 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <!-- Judul Chat Bisa Diklik untuk Reset -->
                <a href="{{ route('chat.index') }}" class="hover:opacity-70 transition duration-150">
                    <h1 class="text-2xl font-bold text-black">Chat</h1>
                </a>
            </div>

            <!-- List Kontak Chat -->
            <div class="overflow-y-auto flex-1">
                @forelse($users as $user)
                    <a href="{{ route('chat.show', $user->id) }}" 
                       class="flex items-center justify-between p-4 border-b border-gray-300 hover:bg-gray-300 transition duration-150 block cursor-pointer {{ isset($activeUser) && $activeUser->id == $user->id ? 'bg-[#C4C4C4]' : '' }}">
                        <div class="flex items-center space-x-3 pointer-events-none">
                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                                 class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h2 class="font-semibold text-gray-900 text-base">{{ $user->name }}</h2>
                                <p class="text-sm text-gray-600 truncate max-w-[150px]">
                                    {{ $user->last_message ? $user->last_message->message : 'Belum ada pesan' }}
                                </p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-600 self-start mt-1 pointer-events-none">
                            {{ $user->last_message ? $user->last_message->created_at->format('H:i') : '' }}
                        </span>
                    </a>
                @empty
                    <div class="p-4 text-center text-gray-600 text-sm">
                        Belum ada pengguna lain.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- AREA CHAT KANAN -->
        <div class="w-2/3 flex flex-col justify-between bg-white">
            @if($activeUser)
                <!-- Header Chat Aktif -->
                <div class="p-4 border-b flex justify-between items-center bg-white shadow-sm">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $activeUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($activeUser->name) }}" 
                             class="w-10 h-10 rounded-full object-cover">
                        <span class="font-semibold text-lg text-gray-800">{{ $activeUser->name }}</span>
                    </div>
                    <!-- Menu Opsi (Titik Tiga) -->
                    <button class="text-gray-600 hover:text-black">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </button>
                </div>

                <!-- Isi Gelembung Pesan -->
                <div class="flex-1 p-6 overflow-y-auto space-y-4">
                    @forelse($messages as $msg)
                        @if($msg->sender_id == auth()->id())
                            <!-- Pesan Pengirim (User Login - Kanan) -->
                            <div class="flex items-end justify-end space-x-2">
                                <div class="bg-[#C4C4C4] text-black px-4 py-2 rounded-2xl max-w-xs text-sm">
                                    {{ $msg->message }}
                                </div>
                                <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                            </div>
                        @else
                            <!-- Pesan Penerima (Kiri) -->
                            <div class="flex items-start space-x-2">
                                <img src="{{ $activeUser->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($activeUser->name) }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                                <div class="border border-gray-300 text-black px-4 py-2 rounded-2xl max-w-xs text-sm bg-white">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-gray-400 text-sm my-auto">
                            Belum ada pesan. Mulai sapa {{ $activeUser->name }}!
                        </div>
                    @endforelse
                </div>

                <!-- Form Input Kirim Pesan -->
                <div class="p-4 bg-white border-t">
                    <form action="{{ route('chat.send', $activeUser->id) }}" method="POST" class="relative flex items-center">
                        @csrf
                        <input type="text" name="message" placeholder="Isi disini" required
                               class="w-full bg-[#D9D9D9] text-gray-800 text-sm rounded-full py-3 pl-5 pr-12 focus:outline-none focus:ring-2 focus:ring-gray-400 placeholder-gray-600">
                        <button type="submit" class="absolute right-3 text-black p-1 hover:opacity-70 font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <!-- Tampilan jika tidak ada user terpilih -->
                <div class="flex-1 flex items-center justify-center text-gray-500">
                    Pilih salah satu kontak untuk memulai percakapan.
                </div>
            @endif
        </div>

    </div>

</body>
</html>