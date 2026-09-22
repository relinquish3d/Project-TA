<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Telah Datang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <nav class="w-full fixed top-0 z-50 flex flex-row h-16 px-6 bg-[#D9D9D9] border-b border-gray-300 items-center justify-between shadow-xs">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-9 h-9 object-contain">
            </a>
            <span class="text-xl font-bold tracking-tight text-black">
            </span>
        </div>

        <!-- Search Bar -->
        <div class="relative flex items-center">
            <span class="absolute left-3.5 text-gray-500 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
            </span>
            <input type="text" class="w-64 rounded-full pl-10 pr-4 py-1.5 text-sm bg-transparent border border-gray-400 text-black placeholder-gray-500 focus:outline-none focus:border-black" placeholder="Cari User...">
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center gap-8">
            <a class="text-gray-800 hover:text-black text-sm font-medium transition-colors" href="#gameFavorite">Game Favorit</a>
            <a class="text-gray-800 hover:text-black text-sm font-medium transition-colors" href="{{ url('/') }}">Home</a>
        </div>

                    <!-- ========== AVATAR BUTTON ========== -->
                    <button
                        id="menuButton"
                        type="button"
                        onclick="toggleMenu(event)"
                        aria-label="Buka menu profil"
                        aria-expanded="false"
                        class="group flex items-center gap-2 rounded-full outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">

                        @if(Auth::user()->avatar)

                            <!-- Avatar dari database -->
                            <img
                                src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                alt="{{ Auth::user()->name }}"
                                class="h-10 w-10 rounded-full border-2 border-gray-400 object-cover shadow-sm transition group-hover:border-black">

                        @else

                            <!-- Avatar default -->
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-gray-500 bg-gray-400 text-sm font-bold text-white shadow-sm transition group-hover:border-black">

                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                            </div>

                        @endif

                        <!-- Ikon panah dropdown -->
                        <i
                            data-lucide="chevron-down"
                            class="h-4 w-4 text-gray-700 transition-transform duration-200"
                            id="chevronIcon">
                        </i>

                    </button>


                    <!-- ========== DROPDOWN MENU ========== -->
                    <div
                        id="dropdownMenu"
                        class="absolute right-0 top-14 hidden w-56 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">

                        <!-- User Information -->
                        <div class="border-b border-gray-200 px-4 py-3">

                            <p class="truncate text-sm font-semibold text-gray-800">
                                {{ Auth::user()->name }}
                            </p>

                            <p class="truncate text-xs text-gray-500">
                                {{ Auth::user()->email }}
                            </p>

                        </div>

                        <!-- Menu Link: Manajemen Tiket Laporan -->
                        <a href="#adminReportsSection" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                            <i data-lucide="inbox" class="w-4 h-4 text-blue-600"></i>
                            <span>Tiket Laporan</span>
                        </a>

                        <div class="border-t border-gray-200 my-1"></div>

                        <!-- Opsi Logout -->
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
    </nav>

    <!-- Main Admin Content -->
    <main class="max-w-7xl mx-auto px-6 pt-24 pb-12" id="adminReportsSection">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-sm text-gray-600 mt-1">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>. Kelola tiket laporan masalah akun pengguna di sini.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $reports->where('status', 'in_progress')->count() }} Sedang Diproses
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $reports->where('status', 'completed')->count() }} Selesai
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg hover:text-green-900">&times;</button>
            </div>
        @endif

        <!-- Laporan Table / Card Section -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="inbox" class="w-5 h-5 text-blue-600"></i>
                    <h2 class="text-base font-bold text-gray-800">Daftar Tiket Laporan User</h2>
                </div>
                <span class="text-xs text-gray-500">Total: {{ $reports->count() }} tiket</span>
            </div>

            <div class="divide-y divide-gray-100 overflow-x-auto">
                @forelse($reports as $report)
                    <div class="p-6 hover:bg-gray-50/50 transition">
                        <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2.5">
                                    <span class="px-2.5 py-1 text-xs font-mono font-bold bg-gray-100 border border-gray-300 rounded-lg text-gray-800">
                                        {{ $report->ticket_code }}
                                    </span>
                                    <span class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 rounded-lg">
                                        {{ $report->category_label }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ $report->created_at ? $report->created_at->format('d M Y, H:i') : '-' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600">
                                    Pelapor: <strong class="text-gray-800">{{ $report->user->name ?? 'User #' . $report->user_id }}</strong> ({{ $report->user->email ?? '-' }})
                                </p>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                @if($report->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600 animate-pulse"></i>
                                        Sedang Diproses
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Deskripsi Laporan -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-4 text-sm text-gray-800 leading-relaxed whitespace-pre-line">
                            {{ $report->description }}
                        </div>

                        @if($report->attachment)
                            <div class="mb-4 flex items-center gap-2">
                                <span class="text-xs font-semibold text-gray-500">Bukti:</span>
                                <a href="{{ asset('storage/' . $report->attachment) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                                    <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                    Lihat Lampiran Gambar
                                </a>
                            </div>
                        @endif

                        <!-- Form Update Status Admin -->
                        <form method="POST" action="{{ route('admin.reports.status', $report->id) }}" class="bg-gray-100/70 p-4 rounded-xl border border-gray-200 flex flex-wrap items-end gap-3">
                            @csrf
                            @method('PATCH')
                            
                            <div class="w-full sm:w-48">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Ubah Status</label>
                                <select name="status" class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-300 bg-white focus:outline-none focus:ring-1 focus:ring-black">
                                    <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>Sedang Diproses (In Progress)</option>
                                    <option value="completed" {{ $report->status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                                </select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Catatan / Tanggapan Admin untuk Pengguna</label>
                                <input type="text" name="admin_notes" value="{{ $report->admin_notes }}" placeholder="Tuliskan keterangan penyelesaian laporan..." class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-300 bg-white focus:outline-none focus:ring-1 focus:ring-black">
                            </div>

                            <button type="submit" class="px-4 py-1.5 text-xs font-semibold text-white bg-black hover:bg-gray-800 rounded-lg shadow-xs transition flex items-center gap-1.5">
                                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                Perbarui Tiket
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500 text-sm">
                        Belum ada tiket laporan yang masuk dari pengguna.
                    </div>
                @endforelse
            </div>
        </div>
    </main>
    <!-- ================= JAVASCRIPT ================= -->
    <script>

        // Aktifkan ikon Lucide
        lucide.createIcons();


        // Fungsi buka/tutup dropdown
        function toggleMenu(event) {

            event.stopPropagation();

            const dropdown = document.getElementById('dropdownMenu');
            const button = document.getElementById('menuButton');
            const chevron = document.getElementById('chevronIcon');

            if (!dropdown || !button) return;

            const isHidden = dropdown.classList.contains('hidden');

            if (isHidden) {

                // Tampilkan dropdown
                dropdown.classList.remove('hidden');

                button.setAttribute('aria-expanded', 'true');

                // Putar ikon panah
                if (chevron) {
                    chevron.classList.add('rotate-180');
                }

            } else {

                // Sembunyikan dropdown
                dropdown.classList.add('hidden');

                button.setAttribute('aria-expanded', 'false');

                // Kembalikan ikon panah
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                }

            }

        }


        // Tutup dropdown jika klik di luar
        document.addEventListener('click', function(event) {

            const dropdown = document.getElementById('dropdownMenu');
            const button = document.getElementById('menuButton');
            const chevron = document.getElementById('chevronIcon');

            if (!dropdown || !button) return;

            if (
                !dropdown.contains(event.target) &&
                !button.contains(event.target)
            ) {

                dropdown.classList.add('hidden');

                button.setAttribute('aria-expanded', 'false');

                if (chevron) {
                    chevron.classList.remove('rotate-180');
                }

            }

        });

    </script>
 
</body>

</html>