<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PlayAll - User Dashboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F8F9FD] min-h-screen text-gray-800 antialiased selection:bg-[#4C49ED] selection:text-white">

    @if(!isset($openChat) || !$openChat)
    <!-- Loading Screen -->
    <div id="pageLoader"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white transition-opacity duration-300">
        <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="h-14 w-14 animate-pulse object-contain">
        <h2 class="mt-3 text-xl font-bold">PlayAll</h2>
        <p class="mt-2 text-sm text-gray-500">Memuat dashboard...</p>
        <div class="mt-5 h-7 w-7 animate-spin rounded-full border-4 border-gray-200 border-t-black"></div>
    </div>
    @endif

    {{--NAVBAR--}}
    <nav class="w-full fixed top-0 left-0 z-50 bg-[#D9D9D9] border-b border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] h-18">
        <div class="max-w-10xl mx-auto h-full px-10 flex items-center justify-between">

            <!-- Brand Logo -->
            <div class="flex items-center gap-5">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-9 h-9 object-contain transition-transform duration-200 group-hover:scale-105">
                    <span class="text-xl font-extrabold text-gray-900">
                        PlayAll
                    </span>
                </a>
            </div>

            <!-- Center Navigation Links -->
            <div class="hidden md:flex items-center gap-8 h-full">
                <!-- Dashboard (Active) -->
                <a href="{{ route('dashboard') }}" class="relative flex items-center gap-2 h-full text-sm font-bold text-gray">
                    <i data-lucide="layout-grid" class="w-4 h-4 stroke-[2.5]"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Cari Teman -->
                <a href="{{ route('friends.index') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Cari Teman</span>
                </a>

                <!-- Chat -->
                <a href="{{ route('chat.index') }}" onclick="event.preventDefault(); openChatSidebar();" class="flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors cursor-pointer">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    <span>Chat</span>
                </a>

                <!-- Game Favorit -->
                <a href="#gameFavoritSection" class="flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                    <i data-lucide="gamepad-2" class="w-4 h-4"></i>
                    <span>Game Favorit</span>
                </a>
            </div>

            <!-- Right Profile & Undangan Terbaru Dropdown Button -->
            <div class="flex items-center gap-4">

                {{-- Profile Section --}}
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 p-1 rounded-full hover:bg-gray-300 transition cursor-pointer focus:outline-none">
                        <div class="relative">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 shadow-xs">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-900 text-white font-bold text-sm flex items-center justify-center border-2 border-gray-300 shadow-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                        </div>

                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-sm font-bold text-gray-900 leading-tight">
                                {{ Auth::user()->display_name ?? Auth::user()->name }}
                            </span>
                            <span class="text-[11px] font-medium text-gray-400 leading-tight">
                                Online
                            </span>
                        </div>

                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200" id="profileChevron"></i>
                    </button>

                    <!-- Profile Dropdown Menu -->
                    <div id="profileDropdownMenu" class="hidden absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl shadow-gray-900/5 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-xs text-gray-400 font-medium">Masuk sebagai</p>
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-black transition">
                            <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                            <span>Edit Profil</span>
                        </a>

                        <a href="{{ route('inbox') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-black transition">
                            <i data-lucide="inbox" class="w-4 h-4 text-gray-400"></i>
                            <span>Kotak Pesan Tiket</span>
                        </a>

                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-[#4C49ED] hover:bg-indigo-50 transition">
                            <i data-lucide="shield" class="w-4 h-4 text-[#4C49ED]"></i>
                            <span>Dashboard Admin</span>
                        </a>
                        @endif

                        <div class="border-t border-gray-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 transition">
                                <i data-lucide="log-out" class="w-4 h-4 text-rose-500"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Dropdown Permintaan Berteman (Add Friend) --}}
                <div class="relative">
                    <button id="inviteDropdownBtn"
                            class="relative w-10 h-10 rounded-full bg-[#D9D9D9] hover:bg-gray-200/80 text-gray-700 flex items-center justify-center transition-all duration-200 cursor-pointer focus:outline-none group"
                            title="Permintaan Berteman">
                        <i data-lucide="user-plus" class="w-5 h-5 text-gray-600 group-hover:text-gray-900 transition-colors"></i>

                        @if(($pendingInvitesCount ?? 0) > 0)
                        <span id="inviteBadge" class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 bg-[#4C49ED] text-white text-[11px] font-bold rounded-full flex items-center justify-center border-2 border-white shadow-xs">
                            {{ $pendingInvitesCount }}
                        </span>
                        @endif
                    </button>

                    <!-- Floating Dropdown Permintaan Berteman -->
                    <div id="inviteDropdownMenu" class="hidden absolute right-0 mt-3 w-88 sm:w-96 bg-white border border-gray-150 rounded-2xl shadow-2xl shadow-indigo-950/10 p-5 z-50 animate-in fade-in slide-in-from-top-2 duration-150">

                        <!-- Header Dropdown -->
                        <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <h3 class="font-extrabold text-base text-gray-900">Permintaan Berteman</h3>
                                <span id="inviteCountLabel" class="bg-indigo-50 text-[#4C49ED] text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ $pendingInvitesCount ?? 0 }} baru
                                </span>
                            </div>
                            <button id="closeInviteDropdown" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <!-- Subtitle -->
                        <p class="text-xs text-gray-500 mt-2.5 mb-3.5">
                            Pengguna yang meminta berteman denganmu
                        </p>

                        <!-- List Permintaan Berteman -->
                        <div class="space-y-3 max-h-80 overflow-y-auto pr-1" id="inviteListContainer">
                            @forelse($pendingInvites as $inv)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/70 border border-gray-100 hover:bg-gray-50 transition invite-row" data-id="{{ $inv->id_invite ?? $inv->id }}">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        @if($inv->sender && $inv->sender->avatar)
                                            <img src="{{ $inv->sender->avatar_url }}" alt="{{ $inv->sender->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-900 text-white font-bold text-sm flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($inv->sender->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="truncate">
                                            <h4 class="text-sm font-bold text-gray-900 truncate">
                                                {{ $inv->sender->display_name ?? $inv->sender->name ?? 'Gamer' }}
                                            </h4>
                                            <p class="text-xs text-gray-500 truncate">Mengirim permintaan berteman</p>
                                            <span class="text-[10px] text-gray-400 mt-0.5 block">
                                                {{ $inv->created_at ? $inv->created_at->diffForHumans() : 'Baru saja' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                        <!-- Terima -->
                                        <form action="{{ route('friends.accept', $inv->id_invite ?? $inv->id) }}" method="POST" class="inline m-0 ajax-accept-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-[#4C49ED] hover:bg-indigo-700 text-white flex items-center justify-center transition shadow-xs cursor-pointer" title="Terima Permintaan">
                                                <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                                            </button>
                                        </form>
                                        <!-- Tolak -->
                                        <form action="{{ route('friends.remove', $inv->id_invite ?? $inv->id) }}" method="POST" class="inline m-0 ajax-decline-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-[#FF1818] hover:bg-red-700 text-white flex items-center justify-center transition cursor-pointer" title="Tolak Permintaan">
                                                <i data-lucide="x" class="w-4 h-4 stroke-[2.5]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200" id="emptyInviteNotice">
                                    <i data-lucide="users" class="w-6 h-6 text-gray-400 mx-auto mb-1.5"></i>
                                    <p class="text-xs text-gray-500">Tidak ada permintaan berteman saat ini.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer Link Button (Primary Purple) -->
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('friends.index') }}" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-[#4C49ED] hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-indigo-200 transition-all">
                                <span>Lihat semua di Cari Teman</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </nav>


    {{-- Main Content --}}
    <main class="max-w-10xl mx-auto px-6 pt-26 pb-16">

        <!-- Welcome Header Banner -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-7">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Selamat datang kembali, {{ Auth::user()->name }}!
                </h1>
                <p class="text-sm text-gray-500 font-normal mt-1">
                    Temukan teman mabar, ajak bermain, dan raih kemenangan bersama.
                </p>
            </div>

            <!-- Cari teman mabar Button -->
            <a href="{{ route('friends.index') }}"
               class="inline-flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl bg-[#D9D9D9] hover:bg-[#A5978B] text-black text-sm font-semibold shadow-md transition-all self-start md:self-auto cursor-pointer">
                <i data-lucide="users" class="w-4 h-4 stroke-[2.5]"></i>
                <span>Cari teman mabar</span>
            </a>
        </div>


        <!-- Grid: 2 Columns Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-start">

            <div class="lg:col-span-8 space-y-7">

                <!-- 1. Ringkasan Aktivitas Card -->
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)]">
                    <h2 class="text-base font-extrabold text-gray-900 mb-5 tracking-tight">
                        Ringkasan aktivitas
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                        <!--widget 1: Teman mabar -->
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-[#FAFAFE] border border-gray-100 hover:border-gray-300 transition cursor-pointer group">
                            <div class="w-12 h-12 rounded-xl bg-[#F0EEFF] text-gray-500 group-hover:bg-black group-hover:text-white transition-all flex items-center justify-center shrink-0">
                                <i data-lucide="users" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 group-hover:text-black transition-colors">Teman Mabar</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-3xl font-black text-gray-900 ">
                                        {{ $activeFriendsCount ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Widget 2: Permintaan Terkirim -->
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-[#FAFAFE] border border-gray-100 hover:border-gray-300 transition cursor-pointer group">
                            <div class="w-12 h-12 rounded-xl bg-[#F0EEFF] text-gray-500 group-hover:bg-black group-hover:text-white transition-all flex items-center justify-center shrink-0">
                                <i data-lucide="send" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 group-hover:text-black transition-colors">Permintaan Terkirim</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-3xl font-black text-gray-900 ">
                                        {{ $pendingAddFriendRequestCount ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Widget 3: Permintaan Masuk (Bisa diklik untuk buka dropdown) -->
                        <div onclick="openInviteDropdown()" class="flex items-center gap-4 p-4 rounded-xl bg-[#FAFAFE] border border-gray-100 hover:border-gray-300 transition cursor-pointer group">
                            <div class="w-12 h-12 rounded-xl bg-[#F0EEFF] text-gray-500 group-hover:bg-black group-hover:text-white transition-all flex items-center justify-center shrink-0">
                                <i data-lucide="user-plus" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 group-hover:text-black transition-colors">Permintaan Masuk</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-3xl font-black text-gray-900" id="widgetInviteCount">
                                        {{ $pendingInvitesCount ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


<!-- Container Game Favorit -->
<section id="gameFavoritSection" class="bg-white rounded-2xl border border-gray-300 p-6 shadow-sm">
    <!-- Section Header -->
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Game favorit</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftar game yang kamu pilih saat registrasi</p>
        </div>
        <a href="#" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 hover:text-black hover:underline transition">
            Jelajahi pemain
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <!-- Grid Game Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-7 gap-4">
        @forelse($user->favorite_games_details as $game)
            <div class="group relative flex flex-col rounded-xl p-2.5 transition-all duration-200 hover:bg-slate-50/80 border border-transparent hover:border-slate-200">
                <!-- Poster / Cover Game -->
                <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-slate-100 shadow-sm">
                    <img
                        src="{{ asset($game['image']) }}"
                        alt="{{ $game['name'] }}"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        loading="lazy"
                    />
                </div>

                <!-- Informasi Game & Rating -->
                <div class="mt-3 flex flex-col">
                    <h3 class="font-bold text-sm text-gray-500 line-clamp-1 group-hover:text-black transition-colors">
                        {{ $game['name'] }}
                    </h3>
                </div>
            </div>
        @empty
            <!-- State Kosong jika user belum memilih game -->
            <div class="col-span-full flex flex-col items-center justify-center py-8 px-4 text-center border-2 border-dashed border-slate-200 rounded-xl">
                <p class="text-sm font-medium text-slate-600">Belum ada game favorit yang dipilih.</p>
                <a href="#" class="mt-2 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    + Tambah game favorit sekarang
                </a>
            </div>
        @endforelse
    </div>
</section>

            </div>
            {{-- Fokus pada Chat Terbaru (Opsi 2) --}}
            <div class="lg:col-span-4">

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)] flex flex-col">

                    <!-- Header Chat Terbaru -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <h2 class="text-base font-extrabold text-gray-900 tracking-tight">
                            Friend List
                        </h2>
                        <a href="{{ route('chat.index') }}" onclick="event.preventDefault(); openChatSidebar();" class="text-xs font-bold text-[#4C49ED] hover:underline cursor-pointer">
                            Lihat semua
                        </a>
                    </div>

                    <!-- Chat List Items -->
                    <div class="divide-y divide-gray-50 mt-2 space-y-1">

                        @if(isset($recentChats) && $recentChats->count() > 0)
                            {{-- Riwayat Chat Nyata dari Database --}}
                            @foreach($recentChats as $chatUser)
                            <a href="{{ route('chat.show', $chatUser->id) }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="relative shrink-0">
                                        @if($chatUser->avatar)
                                            <img src="{{ $chatUser->avatar_url }}" alt="{{ $chatUser->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-black text-white font-bold text-sm flex items-center justify-center">
                                                {{ strtoupper(substr($chatUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-[#4C49ED] transition-colors truncate">
                                            {{ $chatUser->display_name ?? $chatUser->name }}
                                        </h4>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">
                                            {{ $chatUser->last_message ? $chatUser->last_message->message : 'Mulai mengobrol' }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 shrink-0 ml-2 whitespace-nowrap">
                                    {{ $chatUser->last_message ? $chatUser->last_message->created_at->format('H:i') : '' }}
                                </span>
                            </a>
                            @endforeach
                        @else
                            {{--Kontak Teman--}}
                            <a href="{{ route('chat.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="relative shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-black text-white font-bold flex items-center justify-center">
                                            <i data-lucide="user" class="w-5 h-5"></i>
                                        </div>
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-[#4C49ED] transition-colors truncate">DimasX</h4>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">Siap, gas mabar nanti malam!</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 shrink-0 ml-2 whitespace-nowrap">10:32</span>
                            </a>

                            <a href="{{ route('chat.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="relative shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-black text-white font-bold flex items-center justify-center">
                                            <i data-lucide="user" class="w-5 h-5"></i>
                                        </div>
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-[#4C49ED] transition-colors truncate">Zennn</h4>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">Push rank setelah ini yuk.</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 shrink-0 ml-2 whitespace-nowrap">09:15</span>
                            </a>

                            <a href="{{ route('chat.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="relative shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-black text-white font-bold flex items-center justify-center">
                                            <i data-lucide="user" class="w-5 h-5"></i>
                                        </div>
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-[#4C49ED] transition-colors truncate">Aldy</h4>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">Ok! Aku invite ya.</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 shrink-0 ml-2 whitespace-nowrap">Kemarin</span>
                            </a>

                            <a href="{{ route('chat.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="relative shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-black text-white font-bold flex items-center justify-center">
                                            <i data-lucide="user" class="w-5 h-5"></i>
                                        </div>
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-[#4C49ED] transition-colors truncate">Nabila</h4>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">Thank you!</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 shrink-0 ml-2 whitespace-nowrap">Kemarin</span>
                            </a>

                            <a href="{{ route('chat.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="relative shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-black text-white font-bold flex items-center justify-center">
                                            <i data-lucide="user" class="w-5 h-5"></i>
                                        </div>
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-[#4C49ED] transition-colors truncate">Farhan</h4>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">Besok jam 8 malam ya.</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 shrink-0 ml-2 whitespace-nowrap">2 hari lalu</span>
                            </a>
                        @endif

                    </div>

                    <!-- Bottom Link -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('chat.index') }}" onclick="event.preventDefault(); openChatSidebar();" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-black hover:underline transition cursor-pointer">
                            <span>Lihat semua chat</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </main>


    <script>
        // Inisialisasi ikon Lucide
        lucide.createIcons();

        // Loading halaman
        const pageLoader = document.getElementById('pageLoader');

        if (pageLoader) {
            window.addEventListener('load', function() {
                pageLoader.classList.add('opacity-0', 'pointer-events-none');

                setTimeout(function() {
                    pageLoader.classList.add('hidden');
                }, 300);
            });
        }

        // Elemen Dropdown
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdownMenu = document.getElementById('profileDropdownMenu');
        const profileChevron = document.getElementById('profileChevron');

        const inviteDropdownBtn = document.getElementById('inviteDropdownBtn');
        const inviteDropdownMenu = document.getElementById('inviteDropdownMenu');
        const closeInviteDropdown = document.getElementById('closeInviteDropdown');
        const inviteBadge = document.getElementById('inviteBadge');

        // Toggle Profile Dropdown
        if (profileDropdownBtn && profileDropdownMenu) {
            profileDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Tutup invite dropdown jika terbuka
                if (inviteDropdownMenu) inviteDropdownMenu.classList.add('hidden');

                const isHidden = profileDropdownMenu.classList.contains('hidden');
                if (isHidden) {
                    profileDropdownMenu.classList.remove('hidden');
                    if (profileChevron) profileChevron.classList.add('rotate-180');
                } else {
                    profileDropdownMenu.classList.add('hidden');
                    if (profileChevron) profileChevron.classList.remove('rotate-180');
                }
            });
        }

        // Toggle Invite Dropdown
        function openInviteDropdown() {
            if (inviteDropdownMenu) {
                inviteDropdownMenu.classList.remove('hidden');
                if (profileDropdownMenu) profileDropdownMenu.classList.add('hidden');
                if (profileChevron) profileChevron.classList.remove('rotate-180');
            }
        }

        if (inviteDropdownBtn && inviteDropdownMenu) {
            inviteDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Tutup profile dropdown jika terbuka
                if (profileDropdownMenu) profileDropdownMenu.classList.add('hidden');
                if (profileChevron) profileChevron.classList.remove('rotate-180');

                inviteDropdownMenu.classList.toggle('hidden');
            });
        }

        if (closeInviteDropdown && inviteDropdownMenu) {
            closeInviteDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                inviteDropdownMenu.classList.add('hidden');
            });
        }

        // Tutup dropdown jika klik di luar
        window.addEventListener('click', function(e) {
            if (profileDropdownMenu && !profileDropdownMenu.contains(e.target) && !profileDropdownBtn.contains(e.target)) {
                profileDropdownMenu.classList.add('hidden');
                if (profileChevron) profileChevron.classList.remove('rotate-180');
            }
            if (inviteDropdownMenu && !inviteDropdownMenu.contains(e.target) && !inviteDropdownBtn.contains(e.target)) {
                inviteDropdownMenu.classList.add('hidden');
            }
        });

        // SweetAlert2 Config dengan Tailwind murni
        const dashboardSwalConfig = {
            customClass: {
                popup: 'rounded-3xl p-6 sm:p-7 shadow-2xl border border-gray-100 font-sans max-w-sm sm:max-w-md bg-white',
                title: 'text-xl font-extrabold text-gray-900 tracking-tight',
                htmlContainer: 'text-sm text-gray-500 mt-2 leading-relaxed',
                actions: 'mt-6 gap-3 flex flex-row justify-center items-center',
                confirmButton: 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-red-600 hover:bg-red-700 shadow-sm transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-500/30',
                cancelButton: 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-xs text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all cursor-pointer focus:outline-none'
            },
            buttonsStyling: false
        };
        const dashboardSwal = Swal.mixin(dashboardSwalConfig);

        function decrementInviteCount() {
            const inviteBadge = document.getElementById('inviteBadge');
            const inviteCountLabel = document.getElementById('inviteCountLabel');
            const widgetInviteCount = document.getElementById('widgetInviteCount');
            const listContainer = document.getElementById('inviteListContainer');

            if (listContainer) {
                const remaining = Math.max(0, listContainer.querySelectorAll('.invite-row').length - 1);
                if (inviteCountLabel) {
                    inviteCountLabel.textContent = `${remaining} baru`;
                }
                if (widgetInviteCount) {
                    widgetInviteCount.textContent = remaining;
                }
                if (inviteBadge) {
                    if (remaining > 0) {
                        inviteBadge.textContent = remaining;
                    } else {
                        inviteBadge.remove();
                    }
                }
                if (remaining === 0) {
                    listContainer.innerHTML = `
                        <div class="p-6 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200" id="emptyInviteNotice">
                            <i data-lucide="users" class="w-6 h-6 text-gray-400 mx-auto mb-1.5"></i>
                            <p class="text-xs text-gray-500">Tidak ada permintaan berteman saat ini.</p>
                        </div>
                    `;
                    lucide.createIcons();
                }
            }
        }

        // Interaksi AJAX Terima Permintaan
        document.querySelectorAll('.ajax-accept-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formEl = this;
                const row = formEl.closest('.invite-row');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch(formEl.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(formEl)
                })
                .then(res => {
                    if (row) {
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            decrementInviteCount();
                            row.remove();
                        }, 300);
                    }
                })
                .catch(err => {
                    formEl.submit();
                });
            });
        });

        // Interaksi AJAX Tolak Permintaan dengan SweetAlert2
        document.querySelectorAll('.ajax-decline-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formEl = this;
                const row = formEl.closest('.invite-row');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                dashboardSwal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, reject it!",
                    cancelButtonText: "Cancel",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(formEl.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: new FormData(formEl)
                        })
                        .then(res => {
                            dashboardSwal.fire({
                                title: "Rejected!",
                                text: "Permintaan pertemanan berhasil ditolak.",
                                icon: "success",
                                confirmButtonText: "OK",
                                customClass: {
                                    ...dashboardSwalConfig.customClass,
                                    confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-[#4C49ED] hover:bg-indigo-700 shadow-sm transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/30'
                                }
                            }).then(() => {
                                if (row) {
                                    row.style.transition = 'all 0.3s ease';
                                    row.style.opacity = '0';
                                    row.style.transform = 'translateX(20px)';
                                    setTimeout(() => {
                                        decrementInviteCount();
                                        row.remove();
                                    }, 300);
                                } else {
                                    window.location.reload();
                                }
                            });
                        })
                        .catch(err => {
                            formEl.submit();
                        });
                    }
                });
            });
        });
    </script>
    {{-- Chat Sidebar Drawer --}}
    @include('auth.chat.sidebar')

</body>

</html>
