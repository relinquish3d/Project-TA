<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox Tiket Laporan - PlayAll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#FDFCF8] min-h-screen font-sans">

    <!-- Header Navigation -->
    <header class="relative bg-[#D9D9D9] py-4 px-8 flex items-center justify-between rounded-b-2xl shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ url('/profile') }}" class="text-black hover:opacity-75 transition" title="Kembali ke profil">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold italic text-black">Inbox Tiket Laporan</h1>
            </div>
        </div>

        <!-- Three-dot Menu -->
        <div class="relative z-40">
            <button id="menuButton" 
                    onclick="toggleMenu(event)" 
                    class="relative p-2 text-black hover:bg-gray-300 rounded-full transition focus:outline-none"
                    aria-label="Options">
                <i data-lucide="more-vertical" class="w-6 h-6"></i>
                @if(isset($inProgressCount) && $inProgressCount > 0)
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

                    <!-- Inbox (Current page) -->
                    <a href="{{ route('inbox') }}" 
                       class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50/50 hover:bg-blue-50 transition">
                        <div class="flex items-center gap-3">
                            <i data-lucide="inbox" class="w-4 h-4 text-blue-600"></i>
                            <span>Inbox</span>
                        </div>
                        @if(isset($inProgressCount) && $inProgressCount > 0)
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                {{ $inProgressCount }} proses
                            </span>
                        @endif
                    </a>

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

    <!-- Main Container -->
    <main class="max-w-5xl mx-auto px-6 py-8">
        
        <!-- Notifikasi Berhasil Kirim Laporan -->
        @if (session('success_report'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    <span>{{ session('success_report') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:text-green-900">&times;</button>
            </div>
        @endif

        <!-- Banner Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Laporan</span>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600">
                    <i data-lucide="inbox" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Sedang Diproses</span>
                    <h3 class="text-2xl font-bold text-amber-700 mt-1">{{ $inProgressCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600">
                    <i data-lucide="clock" class="w-6 h-6 animate-pulse"></i>
                </div>
            </div>

            <div class="bg-white border border-emerald-200 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Selesai Ditangani</span>
                    <h3 class="text-2xl font-bold text-emerald-700 mt-1">{{ $completedCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Filter Tabs & Actions -->
        <div class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 shadow-xs flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 bg-gray-100 p-1.5 rounded-xl text-xs font-semibold">
                <button type="button" onclick="filterInbox('all', this)" class="inbox-filter-btn px-4 py-2 rounded-lg transition bg-white text-black shadow-xs" data-status="all">
                    Semua ({{ $totalCount ?? 0 }})
                </button>
                <button type="button" onclick="filterInbox('in_progress', this)" class="inbox-filter-btn px-4 py-2 rounded-lg text-gray-600 hover:text-black transition" data-status="in_progress">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1.5"></span>
                    Sedang Diproses ({{ $inProgressCount ?? 0 }})
                </button>
                <button type="button" onclick="filterInbox('completed', this)" class="inbox-filter-btn px-4 py-2 rounded-lg text-gray-600 hover:text-black transition" data-status="completed">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                    Selesai ({{ $completedCount ?? 0 }})
                </button>
            </div>

            <button type="button" onclick="openReportModal()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-black bg-[#D9D9D9] hover:bg-gray-300 rounded-xl transition shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Buat Laporan Baru
            </button>
        </div>

        <!-- Ticket Cards Container -->
        <div class="space-y-4" id="ticketsContainer">
            @forelse($reports as $report)
                <div class="ticket-item bg-white border border-gray-200 rounded-2xl p-5 shadow-xs hover:shadow-md transition" data-status="{{ $report->status }}">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-3 pb-3 border-b border-gray-100">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-3 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-800 border border-gray-300 font-mono tracking-wider">
                                {{ $report->ticket_code }}
                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $report->category_label }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
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

                    <div class="mb-3">
                        <span class="text-xs font-semibold text-gray-500 mb-1 block">Deskripsi Masalah:</span>
                        <p class="text-sm text-gray-800 bg-[#FDFCF8] p-3 rounded-xl border border-gray-200 leading-relaxed whitespace-pre-line">
                            {{ $report->description }}
                        </p>
                    </div>

                    @if($report->attachment)
                        <div class="mt-3 flex items-center gap-3">
                            <span class="text-xs font-semibold text-gray-500">Bukti Lampiran:</span>
                            <a href="{{ asset('storage/' . $report->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 hover:underline bg-blue-50 px-3 py-1.5 rounded-xl border border-blue-200">
                                <i data-lucide="image" class="w-4 h-4"></i>
                                Lihat Bukti Lampiran
                            </a>
                        </div>
                    @endif

                    @if($report->admin_notes)
                        <div class="mt-4 p-4 bg-emerald-50/80 border border-emerald-200 rounded-2xl">
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-800 mb-1">
                                <i data-lucide="message-square" class="w-4 h-4 text-emerald-600"></i>
                                Tanggapan / Catatan Admin:
                            </div>
                            <p class="text-xs text-emerald-900 leading-relaxed">{{ $report->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h4 class="text-base font-bold text-gray-700">Belum Ada Tiket Laporan</h4>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto mt-1">Anda belum pernah membuat laporan. Masalah atau keluhan akun yang Anda kirim akan dipantau di halaman ini.</p>
                    <button type="button" onclick="openReportModal()" class="mt-4 px-5 py-2.5 text-xs font-semibold text-black bg-[#D9D9D9] hover:bg-gray-300 rounded-xl transition inline-flex items-center gap-2 shadow-xs">
                        <i data-lucide="flag" class="w-4 h-4"></i>
                        Kirim Laporan Masalah
                    </button>
                </div>
            @endforelse

            <div id="noFilteredTickets" class="hidden bg-white border border-gray-200 rounded-2xl p-10 text-center">
                <p class="text-sm text-gray-500">Tidak ada tiket laporan dengan status ini.</p>
            </div>
        </div>
    </main>

    <!-- REPORT MODAL -->
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
                    <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none text-sm text-gray-700">
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
                    <textarea name="description" rows="4" required placeholder="Jelaskan kronologi atau detail masalah yang Anda temui..." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none text-sm text-gray-700 resize-none"></textarea>
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

    <!-- Scripts -->
    <script>
        lucide.createIcons();

        // Titik Tiga Dropdown
        function toggleMenu(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('dropdownMenu');
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

        function previewFileName(input) {
            const fileNameSpan = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileNameSpan.textContent = `File terpilih: ${input.files[0].name}`;
            } else {
                fileNameSpan.textContent = '';
            }
        }

        // Filter Inbox Items
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

        window.addEventListener('click', function() {
            closeMenu();
        });
    </script>
</body>
</html>
