<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - PlayAll</title>
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
            <h1 class="text-xl font-bold italic text-black">Your profile</h1>
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
                    <!-- Report -->
                    <button type="button" 
                            onclick="openReportModal()" 
                            class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3">
                            <i data-lucide="flag" class="w-4 h-4 text-amber-500"></i>
                            <span>Report</span>
                        </div>
                    </button>

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

                    <div class="border-t border-gray-200 my-1"></div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                            <i data-lucide="log-out" class="w-4 h-4 text-red-600"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- REPORT -->
    <div id="reportModal" onclick="if(event.target === this) closeReportModal()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-lg mx-4 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="reportModalContent">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-[#D9D9D9] border-gray-100">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-bold text-gray-800">Report Akun</h3>
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
                    <textarea name="description" rows="4" required placeholder="Jelaskan kronologi atau detail masalah yang Anda temui..." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-amber-[#D9D9D9] focus:outline-none text-sm text-gray-700 resize-none"></textarea>
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
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-black bg-[#F3F0E9] hover:bg-[#D9D9D9] rounded-xl shadow-md transition flex items-center gap-2">
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
                    <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shadow-xs">
                        <i data-lucide="inbox" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 leading-tight">Inbox Tiket Laporan</h3>
                        <p class="text-xs text-gray-600">Pantau status penanganan laporan masalah akun Anda</p>
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
                        <button type="button" onclick="closeInboxModal(); openReportModal();" class="mt-4 px-4 py-2 text-xs font-semibold text-black bg-[#D9D9D9] hover:bg-gray-300 rounded-xl transition flex items-center gap-2">
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
            <div class="flex items-center justify-between px-6 py-3 bg-gray-50 border-t border-gray-200">
                <span class="text-xs text-gray-500">Status diperbarui otomatis oleh sistem</span>
                <button type="button" onclick="closeInboxModal()" class="px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-200 rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto px-6 py-8">
        
        <!-- notif update profile  -->
        @if (session('status') === 'profile-updated')
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center justify-between">
                <span>Profile berhasil diperbarui!</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg">&times;</button>
            </div>
        @endif

        <!-- notif report -->
        @if (session('success_report'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center justify-between shadow-sm">
                <span>{{ session('success_report') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:text-green-900">&times;</button>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-6 space-y-6">
                    
                    <!-- Upload foto profile-->
                    <div class="flex items-center gap-6">
                        <label for="avatar" class="relative w-28 h-28 flex-shrink-0 cursor-pointer group rounded-full overflow-hidden">
                            <img id="avatarPreview" 
                                 src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="Profile Picture" 
                                 class="w-28 h-28 rounded-full object-cover border-2 border-gray-300 shadow-sm group-hover:opacity-75 transition">
                            
                            <!-- Ikon Kamera -->
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i data-lucide="camera" class="w-8 h-8 text-white"></i>
                            </div>
                        </label>

                        <div>
                            <span class="block text-sm font-bold text-black">Photo Profile</span>
                            <p class="text-xs text-gray-500 mt-0.5">Klik pada foto untuk mengganti</p>
                            <p class="text-[11px] text-gray-400">JPG/PNG, Max 2MB</p>

                            <!-- File Input -->
                            <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewImage(event)">
                            
                            @error('avatar')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Full Name</label>
                        <input type="text" value="{{ $user->name }}" readonly
                            class="w-full bg-gray-300 border border-gray-400 rounded-full px-5 py-2.5 text-gray-600 cursor-not-allowed shadow-inner select-none">
                    </div>

                    <!-- display name  -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Display Name</label>
                        <input type="text" name="display_name" value="{{ old('display_name', $user->display_name) }}"
                            placeholder="Masukkan display name..."
                            class="w-full bg-[#D9D9D9] border border-gray-400 rounded-full px-5 py-2.5 text-black focus:outline-none focus:border-black shadow-inner">
                        @error('display_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Email</label>
                        <input type="email" value="{{ $user->email }}" readonly
                            class="w-full bg-gray-300 border border-gray-400 rounded-full px-5 py-2.5 text-gray-600 cursor-not-allowed shadow-inner select-none">
                    </div>

                    <!-- bio -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Bio</label>
                        <textarea name="bio" rows="3" placeholder="Tuliskan bio singkat..."
                            class="w-full bg-[#D9D9D9] border border-gray-400 rounded-3xl px-5 py-3 text-black focus:outline-none focus:border-black shadow-inner resize-none">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- save -->
                    <div class="pt-2 flex justify-center">
                        <button type="submit" class="bg-[#D9D9D9] border border-black text-black font-bold italic px-10 py-2 rounded-full hover:bg-black hover:text-white transition-all shadow-md">
                            Save
                        </button>
                    </div>

                </div>

                <!-- game favorite dan ulasan -->
                <div class="lg:col-span-6 space-y-8">
                    
                    <!-- Game Favorit -->
                    <div>
                        <h2 class="text-center font-bold italic text-lg mb-4 text-black">Game Favorit</h2>
                        <div class="flex items-center gap-6 justify-center">
                            <div class="w-36 h-36 bg-[#0F1923] border-2 border-red-500 rounded-lg flex items-center justify-center p-3 shadow-md">
                                <img src="{{ asset('images/Game/valorant.png') }}" alt="Valorant" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <h3 class="text-xl font-bold italic text-black mb-2">Valorant</h3>
                                <div class="flex items-center gap-1.5 font-bold text-black">
                                    <i data-lucide="star" class="w-6 h-6 fill-[#FFC107] text-[#FFC107]"></i>
                                    <span class="text-lg">5.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ulasan User -->
                    <div class="bg-[#D9D9D9] border border-black rounded-3xl p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 border-b border-gray-400 pb-3">
                            <i data-lucide="star" class="w-7 h-7 fill-[#FFC107] text-[#FFC107]"></i>
                            <span class="font-bold text-xl text-black">0</span>
                            <span class="font-bold italic text-xl text-black">• Ulasan User (0)</span>
                        </div>

                       
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </form>
    </div>

    <!-- popup dan foto -->
    <script>
        lucide.createIcons();

        // foto profil
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            }
            if (event.target.files && event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
// ikon Lucide
        lucide.createIcons();

        // Titik Tiga
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

        // Report
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
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
            lucide.createIcons();
        }

        function closeInboxModal() {
            const modal = document.getElementById('inboxModal');
            const content = document.getElementById('inboxModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
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

        // Menampilkan nama file yang diunggah
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
    </script>
</body>
</html>