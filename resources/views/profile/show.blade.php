<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->display_name ?? $user->name }} - PlayAll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#FDFCF8] min-h-screen font-sans">

    <!-- Header Navigation -->
    <header class="relative bg-[#D9D9D9] py-4 px-8 flex items-center justify-between rounded-b-2xl shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ url('/dashboard') }}" class="text-black hover:opacity-75 transition">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <h1 class="text-xl font-bold italic text-black">
                @if(Auth::check() && Auth::id() == $user->id)
                    Your profile
                @else
                    User profile
                @endif
            </h1>
        </div>

        <!-- Pop-up Titik Tiga -->
        <div class="absolute right-6 top-3.5 z-40">
            <button id="menuButton" 
                    onclick="toggleMenu(event)" 
                    class="relative p-2 text-black hover:bg-gray-300 rounded-full transition focus:outline-none"
                    aria-label="Options">
                <i data-lucide="more-vertical" class="w-6 h-6"></i>
                @if(isset($inProgressReportsCount) && $inProgressReportsCount > 0)
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-amber-500 rounded-full ring-2 ring-white"></span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" 
                 class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-2xl shadow-xl z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-150 ease-out origin-top-right">
                
                <div class="py-1">
                    @if(Auth::check() && Auth::id() == $user->id)
                        <!-- Link ke Edit Profile -->
                        <a href="{{ route('profile.edit') }}" 
                           class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                            <i data-lucide="edit-3" class="w-4 h-4 text-blue-600"></i>
                            <span>Edit Profile</span>
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                    @endif

                    <!-- Report -->
                    <button type="button" 
                            onclick="openReportModal()" 
                            class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3">
                            <i data-lucide="flag" class="w-4 h-4 text-amber-500"></i>
                            <span>Report</span>
                        </div>
                    </button>

                    @auth
                        <!-- Inbox (Ticketing System) -->
                        <button type="button" 
                                onclick="openInboxModal()" 
                                class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <i data-lucide="inbox" class="w-4 h-4 text-blue-600"></i>
                                <span>Inbox</span>
                            </div>
                            @if(isset($inProgressReportsCount) && $inProgressReportsCount > 0)
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                    {{ $inProgressReportsCount }} proses
                                </span>
                            @elseif(isset($reports) && $reports->count() > 0)
                                <span class="px-2 py-0.5 text-[10px] font-medium rounded-full bg-gray-100 text-gray-600">
                                    {{ $reports->count() }}
                                </span>
                            @endif
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- REPORT MODAL -->
    <div id="reportModal" onclick="if(event.target === this) closeReportModal()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-lg mx-4 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="reportModalContent">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-[#D9D9D9] border-gray-100">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-bold text-gray-800">
                        @if(Auth::check() && Auth::id() == $user->id)
                            Report Masalah
                        @else
                            Laporkan Pengguna ({{ $user->display_name ?? $user->name }})
                        @endif
                    </h3>
                </div>
                <button type="button" onclick="closeReportModal()" class="p-2 text-gray-700 hover:text-gray-900 rounded-full hover:bg-gray-100 transition cursor-pointer" aria-label="Close">
                    <i data-lucide="x" class="w-5 h-5 pointer-events-none"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                
                <!-- Kategori Masalah -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Kategori Masalah</label>
                    <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-amber-[#D9D9D9] focus:outline-none text-sm text-gray-700">
                        <option value="" disabled selected>-- Pilih alasan laporan --</option>
                        <option value="fake_profile">Profil Palsu / Penipuan</option>
                        <option value="harassment">Pelecehan / Kata-kata Kasar</option>
                        <option value="inappropriate_content">Konten Tidak Pantas</option>
                        <option value="spam">Spam / Aktivitas Mencurigakan</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                <!-- Deskripsi Detail -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Detail</label>
                    <textarea name="description" rows="4" required placeholder="Jelaskan kronologi atau detail masalah yang Anda temui..." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-amber-[#D9D9D9] focus:outline-none text-sm text-gray-700 resize-none">@if(Auth::check() && Auth::id() != $user->id)[Laporan terkait user: {{ $user->name }} (ID: {{ $user->id }})]&#10;@endif</textarea>
                </div>

                <!-- Screenshot -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah Bukti (Opsional)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-400 mb-2"></i>
                                <p class="text-xs text-gray-500"><span class="font-semibold">Klik untuk unggah</span> atau seret file ke sini</p>
                                <p class="text-[10px] text-gray-400 mt-1">PNG, JPG, atau JPEG (Maks. 2MB)</p>
                            </div>
                            <input type="file" name="attachment" class="hidden" accept="image/png, image/jpeg" onchange="previewFileName(this)">
                        </label>
                    </div>
                    <span id="fileName" class="text-xs text-gray-500 mt-1 block italic"></span>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeReportModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-black bg-[#F3F0E9] hover:bg-[#D9D9D9] rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Kirim Laporan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- INBOX TICKETING MODAL -->
    <div id="inboxModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-2xl mx-4 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]" id="inboxModalContent">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-[#D9D9D9] border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 leading-tight">Inbox Tiket Laporan</h3>      
                    </div>
                </div>
                <button onclick="closeInboxModal()" class="p-2 text-gray-700 hover:text-black rounded-full hover:bg-white/50 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Filter Status Bar -->
            <div class="px-6 pt-3 pb-3 bg-gray-50 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 bg-gray-200/80 p-1 rounded-xl text-xs font-semibold">
                    <button type="button" onclick="filterInbox('all', this)" class="inbox-filter-btn px-3 py-1.5 rounded-lg transition bg-white text-black shadow-xs" data-status="all">
                        Semua (<span id="countAll">{{ isset($reports) ? $reports->count() : 0 }}</span>)
                    </button>
                    <button type="button" onclick="filterInbox('in_progress', this)" class="inbox-filter-btn px-3 py-1.5 rounded-lg text-gray-600 hover:text-black transition" data-status="in_progress">
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span>
                        Sedang Diproses (<span id="countInProgress">{{ $inProgressReportsCount ?? 0 }}</span>)
                    </button>
                    <button type="button" onclick="filterInbox('completed', this)" class="inbox-filter-btn px-3 py-1.5 rounded-lg text-gray-600 hover:text-black transition" data-status="completed">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>
                        Selesai (<span id="countCompleted">{{ $completedReportsCount ?? 0 }}</span>)
                    </button>
                </div>

                <button type="button" onclick="closeInboxModal(); openReportModal();" class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-800 bg-amber-100 hover:bg-amber-200 border border-amber-300 px-3 py-1.5 rounded-xl transition">
                    <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                    Buat Laporan Baru
                </button>
            </div>

            <!-- Ticket List Container -->
            <div class="p-6 overflow-y-auto space-y-4 flex-1 bg-[#FDFCF8]" id="ticketsContainer">
                @if(isset($reports) && $reports->count() > 0)
                    @foreach($reports as $report)
                        <div class="ticket-item bg-white border border-gray-200 rounded-2xl p-4 shadow-xs hover:shadow-md transition" data-status="{{ $report->status }}">
                            <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-800 border border-gray-300 font-mono">
                                        {{ $report->ticket_code }}
                                    </span>
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $report->category_label }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400">
                                        {{ $report->created_at ? $report->created_at->format('d M Y, H:i') : '' }}
                                    </span>
                                    @if($report->status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-2xs">
                                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-2xs">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600 animate-pulse"></i>
                                            Sedang Diproses
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed">
                                {{ $report->description }}
                            </p>

                            @if($report->attachment)
                                <div class="mt-3 flex items-center gap-3">
                                    <span class="text-xs font-semibold text-gray-500">Bukti Lampiran:</span>
                                    <a href="{{ asset('storage/' . $report->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 hover:underline bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                        Lihat Gambar Lampiran
                                    </a>
                                </div>
                            @endif

                            @if($report->admin_notes)
                                <div class="mt-3 p-3 bg-emerald-50/80 border border-emerald-200 rounded-xl">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-800 mb-1">
                                        <i data-lucide="message-square" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        Tanggapan / Catatan Admin:
                                    </div>
                                    <p class="text-xs text-emerald-900 leading-relaxed">{{ $report->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div id="emptyInboxState" class="py-12 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                            <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-700">Belum Ada Tiket Laporan</h4>
                        <p class="text-xs text-gray-500 max-w-xs mt-1">Anda belum pernah mengirim laporan masalah. Laporan yang Anda buat akan muncul di sini beserta status penanganannya.</p>
                        <button type="button" onclick="closeInboxModal(); openReportModal();" class="mt-4 px-4 py-2 text-xs font-semibold text-black bg-[#D9D9D9] hover:bg-gray-300 rounded-xl transition flex items-center gap-2 cursor-pointer">
                            <i data-lucide="flag" class="w-4 h-4"></i>
                            Buat Laporan Sekarang
                        </button>
                    </div>
                @endif
                <div id="noFilteredTickets" class="hidden py-8 flex flex-col items-center justify-center text-center">
                    <p class="text-xs text-gray-500">Tidak ada tiket dengan status ini.</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end px-6 py-3 bg-gray-50 border-t border-gray-200">
                <button type="button" onclick="closeInboxModal()" class="px-5 py-2.5 text-sm font-semibold text-black bg-[#F3F0E9] hover:bg-[#D9D9D9] rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto px-6 py-8">
        
        <!-- Notif Update / Success -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center justify-between shadow-sm">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:text-green-900">&times;</button>
            </div>
        @endif

        <!-- Notif Report -->
        @if (session('success_report'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center justify-between shadow-sm">
                <span>{{ session('success_report') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:text-green-900">&times;</button>
            </div>
        @endif

        <!-- Notif Error (untuk rating / validasi) -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl flex items-center justify-between shadow-sm">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:text-red-900">&times;</button>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl flex items-center justify-between shadow-sm">
                <span>{{ $errors->first() }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:text-red-900">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Kolom Kiri: Detail Profil User -->
            <div class="lg:col-span-6 space-y-6">
                
                <!-- Foto Profile -->
                <div class="flex items-center gap-6">
                    <div class="relative w-28 h-28 flex-shrink-0 rounded-full overflow-hidden border-2 border-gray-300 shadow-sm bg-gray-100">
                        <img id="avatarPreview" 
                             src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                             alt="{{ $user->name }}" 
                             class="w-28 h-28 rounded-full object-cover">
                    </div>

                    <div>
                        <span class="block text-sm font-bold text-black">Photo Profile</span>
                        <p class="text-xs text-gray-500 mt-0.5">Member since {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                        <p class="text-[11px] text-gray-400">User ID: {{ $user->id }}</p>
                    </div>
                </div>

                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-bold italic text-black mb-1">Full Name</label>
                    <input type="text" value="{{ $user->name }}" readonly
                        class="w-full bg-gray-300 border border-gray-400 rounded-full px-5 py-2.5 text-gray-600 cursor-not-allowed shadow-inner select-none">
                </div>

                <!-- Display Name -->
                <div>
                    <label class="block text-sm font-bold italic text-black mb-1">Display Name</label>
                    <input type="text" value="{{ $user->display_name ?? '-' }}" readonly
                        class="w-full bg-[#D9D9D9] border border-gray-400 rounded-full px-5 py-2.5 text-black shadow-inner select-none cursor-default">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold italic text-black mb-1">Email</label>
                    <input type="email" value="{{ $user->email }}" readonly
                        class="w-full bg-gray-300 border border-gray-400 rounded-full px-5 py-2.5 text-gray-600 cursor-not-allowed shadow-inner select-none">
                </div>

                <!-- Bio -->
                <div>
                    <label class="block text-sm font-bold italic text-black mb-1">Bio</label>
                    <textarea rows="3" readonly
                        class="w-full bg-[#D9D9D9] border border-gray-400 rounded-3xl px-5 py-3 text-black shadow-inner resize-none select-none cursor-default">{{ $user->bio ?: 'Belum ada bio.' }}</textarea>
                </div>

                <!-- Tombol Interaksi (Sebagai Pengganti Tombol Save pada Edit) -->
                <div class="pt-2 flex flex-wrap items-center justify-center gap-4">
                    @auth
                        @if(Auth::id() == $user->id)
                            <!-- Jika Membuka Profil Sendiri -->
                            <a href="{{ route('profile.edit') }}" 
                               class="inline-flex items-center gap-2 bg-[#D9D9D9] border border-black text-black font-bold italic px-10 py-2 rounded-full hover:bg-black hover:text-white transition-all shadow-md">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                Edit Profile
                            </a>
                        @else
                            <!-- Jika Profil Orang Lain: Tombol Pertemanan -->
                            @php
                                $friendship = Auth::user()->inviteWith($user->id);
                            @endphp

                            @if($friendship && $friendship->status === 'accepted')
                                <span class="inline-flex items-center gap-2 bg-emerald-600 border border-emerald-700 text-white font-bold italic px-8 py-2 rounded-full shadow-md select-none">
                                    <i data-lucide="user-check" class="w-4 h-4"></i>
                                    Berteman
                                </span>
                            @elseif($friendship && $friendship->status === 'pending' && $friendship->sender_id === Auth::id())
                                <span class="inline-flex items-center gap-2 bg-amber-500 border border-amber-600 text-white font-bold italic px-8 py-2 rounded-full shadow-md select-none">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                    Permintaan Terkirim
                                </span>
                            @elseif($friendship && $friendship->status === 'pending' && $friendship->receiver_id === Auth::id())
                                <form action="{{ route('friends.accept', $friendship->id) }}" method="POST" class="inline m-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 border border-emerald-700 text-white font-bold italic px-8 py-2 rounded-full transition-all shadow-md cursor-pointer">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                        Terima Pertemanan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('friends.add', $user->id) }}" method="POST" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 bg-[#D9D9D9] border border-black text-black font-bold italic px-8 py-2 rounded-full hover:bg-black hover:text-white transition-all shadow-md cursor-pointer">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                        Add Friend
                                    </button>
                                </form>
                            @endif

                            <!-- Tombol Kirim Chat -->
                            <a href="{{ route('chat.show', $user->id) }}" 
                               class="inline-flex items-center gap-2 bg-[#D9D9D9] border border-black text-black font-bold italic px-8 py-2 rounded-full hover:bg-black hover:text-white transition-all shadow-md cursor-pointer">
                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                Chat
                            </a>
                        @endif
                    @else
                        <!-- Pengunjung Belum Login -->
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center gap-2 bg-[#D9D9D9] border border-black text-black font-bold italic px-10 py-2 rounded-full hover:bg-black hover:text-white transition-all shadow-md">
                            Login untuk Berteman
                        </a>
                    @endauth
                </div>

            </div>

            <!-- Kolom Kanan: Game Favorit dan Ulasan User -->
            <div class="lg:col-span-6 space-y-8">
                
                <!-- Game Favorit -->
                @php
                    $favoriteGame = 'Valorant';
                    $gameImg = 'images/Game/valorant.png';
                    $gameBg = 'bg-[#0F1923] border-red-500';

                    if (!empty($user->favorite_games) && is_array($user->favorite_games) && count($user->favorite_games) > 0) {
                        $fav = $user->favorite_games[0];
                        if (stripos($fav, 'PUBG') !== false) {
                            $favoriteGame = 'PUBG Mobile';
                            $gameImg = 'images/Game/pubg.png';
                            $gameBg = 'bg-[#1E293B] border-amber-500';
                        } elseif (stripos($fav, 'Mobile Legends') !== false || stripos($fav, 'ML') !== false) {
                            $favoriteGame = 'Mobile Legends';
                            $gameImg = 'images/Game/ml.png';
                            $gameBg = 'bg-[#0F172A] border-blue-500';
                        } elseif (stripos($fav, 'FC') !== false) {
                            $favoriteGame = 'EA Sports FC';
                            $gameImg = 'images/Game/fc.png';
                            $gameBg = 'bg-[#0B1320] border-emerald-500';
                        } else {
                            $favoriteGame = $fav;
                        }
                    }
                @endphp
                <div>
                    <h2 class="text-center font-bold italic text-lg mb-4 text-black">Game Favorit</h2>
                    <div class="flex items-center gap-6 justify-center">
                        <div class="w-36 h-36 {{ $gameBg }} border-2 rounded-lg flex items-center justify-center p-3 shadow-md">
                            <img src="{{ asset($gameImg) }}" alt="{{ $favoriteGame }}" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-xl font-bold italic text-black mb-2">{{ $favoriteGame }}</h3>
                            <!-- Rating Rata-rata (dinamis dari database) -->
                            <div class="flex items-center gap-1.5 font-bold text-black">
                                @php
                                    // Hitung bintang penuh, setengah, dan kosong untuk tampilan visual
                                    $fullStars = floor($averageRating);
                                    $hasHalf = ($averageRating - $fullStars) >= 0.25 && ($averageRating - $fullStars) < 0.75;
                                    $roundUp = ($averageRating - $fullStars) >= 0.75;
                                    if ($roundUp) $fullStars++;
                                    $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
                                @endphp
                                {{-- Bintang penuh --}}
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i data-lucide="star" class="w-6 h-6 fill-[#FFC107] text-[#FFC107]"></i>
                                @endfor
                                {{-- Bintang setengah (menggunakan gradient) --}}
                                @if($hasHalf)
                                    <div class="relative w-6 h-6">
                                        <i data-lucide="star" class="w-6 h-6 absolute text-gray-300 fill-gray-300"></i>
                                        <div class="absolute inset-0 overflow-hidden" style="width: 50%">
                                            <i data-lucide="star" class="w-6 h-6 fill-[#FFC107] text-[#FFC107]"></i>
                                        </div>
                                    </div>
                                @endif
                                {{-- Bintang kosong --}}
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i data-lucide="star" class="w-6 h-6 text-gray-300 fill-gray-300"></i>
                                @endfor
                                <span class="text-lg ml-1">{{ number_format($averageRating, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalRatings }} ulasan</p>
                        </div>
                    </div>
                </div>


                <!-- =============================================
                     BAGIAN ULASAN USER
                     Menampilkan rata-rata rating + daftar semua ulasan
                     ============================================= -->
                <div class="bg-[#D9D9D9] border border-black rounded-3xl p-6 shadow-sm">
                    <!-- Header Ulasan: rata-rata bintang + jumlah ulasan -->
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-400 pb-3">
                        <i data-lucide="star" class="w-7 h-7 fill-[#FFC107] text-[#FFC107]"></i>
                        <span class="font-bold text-xl text-black">{{ number_format($averageRating, 2) }}</span>
                        <span class="font-bold italic text-xl text-black">• Ulasan User ({{ $totalRatings }})</span>
                    </div>

                    <!-- Daftar Ulasan / Review dari user lain -->
                    <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                        @if($totalRatings > 0)
                            @foreach($ratings as $rating)
                                <div class="bg-gray-100 border border-gray-300 rounded-2xl p-4 shadow-xs">
                                    <!-- Baris Atas: Info reviewer + tanggal -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <!-- Avatar reviewer -->
                                            <img src="{{ $rating->reviewer->avatar ? asset('storage/' . $rating->reviewer->avatar) : asset('images/default-avatar.png') }}" 
                                                 alt="{{ $rating->reviewer->name }}" 
                                                 class="w-9 h-9 rounded-full object-cover border border-gray-300">
                                            <div>
                                                <!-- Nama reviewer -->
                                                <a href="{{ route('profile.show', $rating->reviewer->id) }}" 
                                                   class="text-sm font-bold text-gray-800 hover:underline">
                                                    {{ $rating->reviewer->display_name ?? $rating->reviewer->name }}
                                                </a>
                                                <!-- Tanggal review -->
                                                <p class="text-[11px] text-gray-400">{{ $rating->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                        <!-- Bintang yang diberikan -->
                                        <div class="flex items-center gap-0.5">
                                            @php
                                                $rFullStars = floor($rating->stars);
                                                $rHasHalf = ($rating->stars - $rFullStars) >= 0.25 && ($rating->stars - $rFullStars) < 0.75;
                                                $rRoundUp = ($rating->stars - $rFullStars) >= 0.75;
                                                if ($rRoundUp) $rFullStars++;
                                                $rEmptyStars = 5 - $rFullStars - ($rHasHalf ? 1 : 0);
                                            @endphp
                                            @for($i = 0; $i < $rFullStars; $i++)
                                                <i data-lucide="star" class="w-4 h-4 fill-[#FFC107] text-[#FFC107]"></i>
                                            @endfor
                                            @if($rHasHalf)
                                                <div class="relative w-4 h-4">
                                                    <i data-lucide="star" class="w-4 h-4 absolute text-gray-300 fill-gray-300"></i>
                                                    <div class="absolute inset-0 overflow-hidden" style="width: 50%">
                                                        <i data-lucide="star" class="w-4 h-4 fill-[#FFC107] text-[#FFC107]"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            @for($i = 0; $i < $rEmptyStars; $i++)
                                                <i data-lucide="star" class="w-4 h-4 text-gray-300 fill-gray-300"></i>
                                            @endfor
                                            <span class="text-xs font-bold text-gray-600 ml-1">{{ number_format($rating->stars, 1) }}</span>
                                        </div>
                                    </div>
                                    <!-- Isi ulasan / komentar -->
                                    <p class="text-sm text-gray-700 bg-gray-200/70 p-3 rounded-xl border border-gray-300/60 leading-relaxed">
                                        {{ $rating->comment ?: 'Tidak ada ulasan.' }}
                                    </p>
                                </div>
                            @endforeach
                        @else
                            <!-- State kosong jika belum ada ulasan -->
                            <div class="py-6 flex flex-col items-center justify-center text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                                    <i data-lucide="star" class="w-6 h-6 text-gray-400"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-700">Belum Ada Ulasan</h4>
                                <p class="text-xs text-gray-500 max-w-xs mt-1">Belum ada pengguna lain yang memberikan rating untuk user ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- =============================================
                     TOMBOL TAMBAHKAN RATING
                     Hanya tampil jika user sedang login & bukan profil sendiri
                     ============================================= -->
                @auth
                    @if(Auth::id() != $user->id)
                        <button type="button" onclick="openRatingModal()"
                            class="inline-flex items-center gap-2 bg-[#D9D9D9] border border-black text-black font-bold italic px-8 py-2 rounded-full hover:bg-black hover:text-white transition-all shadow-md cursor-pointer">
                            <i data-lucide="plus" class="w-6 h-6"></i>
                            {{ $existingRating ? 'Edit Rating Saya' : 'Tambahkan Rating' }}
                        </button>
                    @endif
                @endauth
            </div>

        </div>
    </div>

    <!-- =============================================
         RATING MODAL (Pop-up)
         Style disesuaikan dengan Inbox Modal agar konsisten.
         Fitur: bintang interaktif (mendukung setengah/half-star),
                input ulasan opsional, tombol kirim.
         ============================================= -->
    @auth
        @if(Auth::id() != $user->id)
        <div id="ratingModal" onclick="if(event.target === this) closeRatingModal()" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
            <div class="bg-white w-full max-w-lg mx-4 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="ratingModalContent">
                
                <!-- Modal Header (sama style dengan inbox) -->
                <div class="flex items-center justify-between px-6 py-4 bg-[#D9D9D9] border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <i data-lucide="star" class="w-5 h-5 fill-[#FFC107] text-[#FFC107]"></i>
                        <h3 class="text-lg font-bold text-gray-800 leading-tight">
                            {{ $existingRating ? 'Edit Rating' : 'Berikan Rating' }}
                        </h3>
                    </div>
                    <button type="button" onclick="closeRatingModal()" class="p-2 text-gray-700 hover:text-black rounded-full hover:bg-white/50 transition cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5 pointer-events-none"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form action="{{ route('rating.store', $user->id) }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Info target user yang akan dirating -->
                    <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                             alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-300">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $user->display_name ?? $user->name }}</p>
                            <p class="text-[11px] text-gray-500">Berikan penilaian untuk user ini</p>
                        </div>
                    </div>

                    <!-- =============================================
                         INTERAKTIF STAR RATING (mendukung half-star / setengah bintang)
                         Klik kiri bintang = setengah, klik kanan = penuh
                         Contoh: klik kiri bintang ke-4 = 3.5, klik kanan = 4.0
                         ============================================= -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Rating Bintang</label>
                        
                        <!-- Container bintang interaktif -->
                        <div class="flex items-center justify-center gap-1 py-3 px-4 bg-[#d9d9d9] rounded-2xl">
                            @for($s = 1; $s <= 5; $s++)
                                <div class="star-container relative cursor-pointer w-10 h-10" data-star="{{ $s }}">
                                    <!-- Bintang kosong (background) -->
                                    <svg class="w-10 h-10 absolute inset-0 star-empty" viewBox="0 0 24 24" fill="#a8a8a8" stroke="#8c8c8c" stroke-width="1">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                    <!-- Bintang setengah (half) -->
                                    <div class="absolute inset-0 overflow-hidden star-half-wrapper" style="width: 50%; display: none;">
                                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FFC107" stroke="#FFC107" stroke-width="1">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                    </div>
                                    <!-- Bintang penuh (full) -->
                                    <svg class="w-10 h-10 absolute inset-0 star-full" viewBox="0 0 24 24" fill="#FFC107" stroke="#FFC107" stroke-width="1" style="display: none;">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                </div>
                            @endfor
                        </div>

                        <!-- Tampilkan nilai rating yang dipilih -->
                        <div class="text-center mt-2">
                            <span id="ratingValueDisplay" class="inline-block px-3 py-1 bg-gray-100 text-sm font-bold text-gray-700 rounded-full border border-gray-200">
                                {{ $existingRating ? number_format($existingRating->stars, 1) . ' / 5.0' : 'Belum memilih rating' }}
                            </span>
                        </div>

                        <!-- Hidden input untuk nilai rating -->
                        <input type="hidden" name="stars" id="ratingInput" value="{{ $existingRating ? $existingRating->stars : '' }}" required>
                    </div>

                    <!-- Input Ulasan (opsional) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ulasan (Opsional)</label>
                        <textarea name="comment" rows="3" maxlength="500" 
                                  placeholder="Tulis ulasan Anda tentang user ini... (Maks. 500 karakter)"
                                  class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-100 focus:bg-white focus:ring-2 focus:ring-amber-400 focus:outline-none text-sm text-gray-700 resize-none">{{ $existingRating ? $existingRating->comment : '' }}</textarea>
                        <p class="text-[11px] text-gray-400 mt-1">Jika tidak diisi, akan ditampilkan sebagai "Tidak ada ulasan"</p>
                    </div>

                    <!-- Tombol Aksi (kirim / batal) -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" onclick="closeRatingModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" id="submitRatingBtn" class="px-5 py-2.5 text-sm font-semibold text-black bg-[#F3F0E9] hover:bg-[#D9D9D9] rounded-xl shadow-md transition flex items-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $existingRating ? '' : 'disabled' }}>
                            <i data-lucide="send" class="w-4 h-4"></i>
                            {{ $existingRating ? 'Update Rating' : 'Kirim Rating' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
        @endif
    @endauth

    <!-- Scripts -->
    <script>
        // Inisialisasi Lucide Icons
        lucide.createIcons();

        // Titik Tiga Dropdown
        function toggleMenu(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('dropdownMenu');
            if (!dropdown) return;
            const isHidden = dropdown.classList.contains('hidden');

            if (isHidden) {
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'scale-95');
                    dropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                closeMenu();
            }
        }

        function closeMenu() {
            const dropdown = document.getElementById('dropdownMenu');
            if (!dropdown) return;
            dropdown.classList.remove('opacity-100', 'scale-100');
            dropdown.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 150);
        }

        // Report Modal
        function openReportModal() {
            closeMenu();
            const modal = document.getElementById('reportModal');
            const content = document.getElementById('reportModalContent');
            
            if (modal && content) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }, 10);
            }
            lucide.createIcons();
        }

        function closeReportModal() {
            const modal = document.getElementById('reportModal');
            const content = document.getElementById('reportModalContent');
            
            if (modal && content) {
                modal.classList.add('opacity-0');
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Inbox Modal
        function openInboxModal() {
            closeMenu();
            const modal = document.getElementById('inboxModal');
            const content = document.getElementById('inboxModalContent');
            
            if (modal && content) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }, 10);
                lucide.createIcons();
            }
        }

        function closeInboxModal() {
            const modal = document.getElementById('inboxModal');
            const content = document.getElementById('inboxModalContent');
            
            if (modal && content) {
                modal.classList.add('opacity-0');
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        function filterInbox(status, btn) {
            const buttons = document.querySelectorAll('.inbox-filter-btn');
            buttons.forEach(b => {
                b.classList.remove('bg-white', 'text-black', 'shadow-xs');
                b.classList.add('text-gray-600');
            });
            if (btn) {
                btn.classList.add('bg-white', 'text-black', 'shadow-xs');
                btn.classList.remove('text-gray-600');
            }

            const items = document.querySelectorAll('.ticket-item');
            let visibleCount = 0;
            items.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                if (status === 'all' || itemStatus === status) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const noTicketsEl = document.getElementById('noFilteredTickets');
            if (noTicketsEl) {
                if (visibleCount === 0 && items.length > 0) {
                    noTicketsEl.classList.remove('hidden');
                } else {
                    noTicketsEl.classList.add('hidden');
                }
            }
        }

        // Menampilkan nama file lampiran yang diunggah
        function previewFileName(input) {
            const fileNameSpan = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileNameSpan.textContent = `File terpilih: ${input.files[0].name}`;
            } else {
                fileNameSpan.textContent = '';
            }
        }

        // Tutup dropdown jika klik di luar area
        window.addEventListener('click', function() {
            closeMenu();
        });

        // =============================================
        // RATING MODAL: Buka & Tutup
        // Fungsi openRatingModal() dan closeRatingModal()
        // menggunakan animasi yang sama dengan inbox modal
        // =============================================
        function openRatingModal() {
            const modal = document.getElementById('ratingModal');
            const content = document.getElementById('ratingModalContent');
            
            if (modal && content) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }, 10);
                lucide.createIcons();
                // Render ulang bintang jika sudah ada nilai sebelumnya
                const existingValue = document.getElementById('ratingInput')?.value;
                if (existingValue) {
                    renderStars(parseFloat(existingValue));
                }
            }
        }

        function closeRatingModal() {
            const modal = document.getElementById('ratingModal');
            const content = document.getElementById('ratingModalContent');
            
            if (modal && content) {
                modal.classList.add('opacity-0');
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // =============================================
        // INTERAKTIF STAR RATING
        // Mendukung half-star (setengah bintang):
        //   - Klik SISI KIRI bintang  = nilai setengah (contoh: bintang ke-3 kiri = 2.5)
        //   - Klik SISI KANAN bintang = nilai penuh   (contoh: bintang ke-3 kanan = 3.0)
        //
        // Cara kerja:
        //   1. Setiap bintang punya data-star (1-5)
        //   2. Saat diklik, cek posisi klik (kiri/kanan dari tengah bintang)
        //   3. Update hidden input "stars" + tampilan visual
        // =============================================
        let currentRating = {{ $existingRating ? $existingRating->stars : 0 }};

        document.querySelectorAll('.star-container').forEach(container => {
            // Event: klik bintang untuk set rating
            container.addEventListener('click', function(e) {
                const starNum = parseInt(this.getAttribute('data-star'));
                const rect = this.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const halfWidth = rect.width / 2;

                // Klik di sisi kiri = setengah bintang, sisi kanan = penuh
                if (clickX <= halfWidth) {
                    currentRating = starNum - 0.5;
                } else {
                    currentRating = starNum;
                }

                // Update hidden input & tampilan
                document.getElementById('ratingInput').value = currentRating;
                document.getElementById('ratingValueDisplay').textContent = currentRating.toFixed(1) + ' / 5.0';
                
                // Aktifkan tombol submit
                const submitBtn = document.getElementById('submitRatingBtn');
                if (submitBtn) submitBtn.disabled = false;

                // Render visual bintang
                renderStars(currentRating);
            });

            // Event: hover preview (tampilkan preview bintang saat mouse over)
            container.addEventListener('mousemove', function(e) {
                const starNum = parseInt(this.getAttribute('data-star'));
                const rect = this.getBoundingClientRect();
                const hoverX = e.clientX - rect.left;
                const halfWidth = rect.width / 2;

                let previewRating;
                if (hoverX <= halfWidth) {
                    previewRating = starNum - 0.5;
                } else {
                    previewRating = starNum;
                }
                renderStars(previewRating);
            });

            // Event: mouse leave (kembalikan ke rating yang sudah dipilih)
            container.addEventListener('mouseleave', function() {
                renderStars(currentRating);
            });
        });

        /**
         * Render tampilan visual bintang berdasarkan nilai rating.
         * 
         * @param {number} rating - Nilai rating (0 - 5, bisa desimal 0.5)
         * 
         * Logika:
         *   - Bintang penuh: star index <= floor(rating)
         *   - Bintang setengah: star index === ceil(rating) DAN rating bukan bilangan bulat
         *   - Bintang kosong: sisanya
         */
        function renderStars(rating) {
            document.querySelectorAll('.star-container').forEach(container => {
                const starNum = parseInt(container.getAttribute('data-star'));
                const fullSvg = container.querySelector('.star-full');
                const halfWrapper = container.querySelector('.star-half-wrapper');
                const emptySvg = container.querySelector('.star-empty');

                // Reset semua ke kosong
                fullSvg.style.display = 'none';
                halfWrapper.style.display = 'none';

                if (starNum <= Math.floor(rating)) {
                    // Bintang penuh (kuning)
                    fullSvg.style.display = 'block';
                } else if (starNum === Math.ceil(rating) && rating % 1 !== 0) {
                    // Bintang setengah (half-star)
                    halfWrapper.style.display = 'block';
                }
                // Else: tetap kosong (abu-abu) — sudah default
            });
        }

        // Render bintang awal jika sudah ada rating sebelumnya
        if (currentRating > 0) {
            renderStars(currentRating);
        }
    </script>
</body>
</html>