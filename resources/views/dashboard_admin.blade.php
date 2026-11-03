<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - PlayAll</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gray-100 text-gray-900 antialiased">

    <!-- Loading Screen -->
    <div id="pageLoader"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white transition-opacity duration-300">
        <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="h-14 w-14 animate-pulse object-contain">
        <h2 class="mt-3 text-xl font-bold">PlayAll</h2>
        <p class="mt-2 text-sm text-gray-500">Memuat dashboard...</p>
        <div class="mt-5 h-7 w-7 animate-spin rounded-full border-4 border-gray-200 border-t-black"></div>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-black/40"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-gray-200 bg-white transition-transform duration-300">

        <div class="flex h-16 items-center justify-between border-b border-gray-200 px-5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="h-9 w-9 object-contain">
                <span class="text-xl font-bold">PlayAll</span>
            </div>

            <button id="closeSidebarButton" type="button" aria-label="Tutup sidebar"
                class="rounded-lg p-2 hover:bg-gray-100">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>


        <nav class="flex-1 space-y-1 overflow-y-auto p-4">

            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                Menu Utama
            </p>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 rounded-lg bg-gray-200 px-3 py-2.5 text-sm font-semibold text-gray-900">
                <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
                Dashboard
            </a>

            <!-- Tiket Laporan -->
            <a href="{{ route('inbox') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="ticket" class="h-5 w-5"></i>
                Tiket Laporan
            </a>

            <!-- Kelola Admin -->
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="users" class="h-5 w-5"></i>
                Kelola Admin
            </a>

            <!-- Kategori Game -->
            <a href="{{ route('admin.categories.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="tags" class="h-5 w-5"></i>
                Kategori Game
            </a>

            <div class="my-4 border-t border-gray-200"></div>

            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                Pengaturan
            </p>

            <!-- Profil -->
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="user-round" class="h-5 w-5"></i>
                Profil Saya
            </a>

            <!-- Kembali ke Website -->
            <a href="{{ url('/') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="house" class="h-5 w-5"></i>
                Kembali ke Website
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-600 transition hover:bg-gray-100 hover:text-gray-900">
                    <i data-lucide="log-out" class="h-5 w-5"></i>
                    Logout
                </button>
            </form>

        </nav>


        <div class="border-t border-gray-200 p-4 text-xs text-gray-400">
            PlayAll Admin Panel
        </div>
    </aside>

    <!-- Navbar -->
    <header
        class="fixed left-0 right-0 top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">

        <div class="flex items-center gap-3">
            <button id="sidebarButton" type="button" aria-label="Buka sidebar"
                class="rounded-lg p-2 hover:bg-gray-100">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>
            <span class="hidden text-sm font-semibold text-gray-500 sm:block">
                Admin Panel
            </span>
        </div>

        <!-- Search User -->
        <div class="relative mx-3 hidden w-full max-w-sm md:block">
            <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>

            <input id="searchInput" type="text" autocomplete="off" placeholder="Cari user..."
                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-sm outline-none focus:border-gray-400 focus:bg-white">

            <div id="searchDropdown"
                class="absolute left-0 right-0 top-full z-50 mt-2 hidden max-h-72 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg">
            </div>
        </div>

        <!-- Admin Profile -->
        <div class="relative">
            <button id="menuButton" type="button" aria-expanded="false"
                class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-gray-100">

                @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                        class="h-9 w-9 rounded-full border border-gray-200 object-cover">
                @else
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-800 text-sm font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <div class="hidden text-left sm:block">
                    <p class="max-w-32 truncate text-sm font-semibold">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>

                <i data-lucide="chevron-down" id="chevronIcon" class="hidden h-4 w-4 sm:block"></i>
            </button>

            <div id="dropdownMenu"
                class="absolute right-0 top-12 z-50 hidden w-56 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">

                <div class="border-b border-gray-200 px-4 py-3">
                    <p class="truncate text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ url('/profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-100">
                    <i data-lucide="user-round" class="h-4 w-4"></i>
                    Profil Saya
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 border-t border-gray-200 px-4 py-3 text-sm hover:bg-gray-100">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main id="dashboard" class="mx-auto max-w-7xl px-4 pb-12 pt-24 sm:px-6 lg:px-8">

        <!-- Heading -->
        <section class="mb-8">
            <p class="mb-1 text-sm text-gray-500">Ringkasan aktivitas</p>
            <h1 class="text-2xl font-bold sm:text-3xl">Dashboard Admin</h1>
            <p class="mt-2 text-sm text-gray-500">
                Selamat datang kembali, {{ Auth::user()->name }}.
                Berikut ringkasan website PlayAll.
            </p>
        </section>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 rounded-lg border border-gray-300 bg-white p-4 text-sm">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistik -->
        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Total User</p>
                    <i data-lucide="users" class="h-5 w-5 text-gray-500"></i>
                </div>
                <p class="mt-3 text-3xl font-bold">{{ $totalUsers }}</p>
                <p class="mt-1 text-xs text-gray-400">Akun terdaftar</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Total Tiket</p>
                    <i data-lucide="ticket" class="h-5 w-5 text-gray-500"></i>
                </div>
                <p class="mt-3 text-3xl font-bold">{{ $reportStats['total'] }}</p>
                <p class="mt-1 text-xs text-gray-400">Semua laporan</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Sedang Diproses</p>
                    <i data-lucide="clock-3" class="h-5 w-5 text-gray-500"></i>
                </div>
                <p class="mt-3 text-3xl font-bold">{{ $reportStats['in_progress'] }}</p>
                <p class="mt-1 text-xs text-gray-400">Menunggu penyelesaian</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Selesai</p>
                    <i data-lucide="check-circle-2" class="h-5 w-5 text-gray-500"></i>
                </div>
                <p class="mt-3 text-3xl font-bold">{{ $reportStats['completed'] }}</p>
                <p class="mt-1 text-xs text-gray-400">Laporan ditangani</p>
            </div>

        </section>

        <!-- Tiket Terbaru -->
        <section id="reports" class="mb-8 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="font-bold">Tiket Laporan Terbaru</h2>
                    <p class="mt-1 text-xs text-gray-500">
                        Laporan yang masuk ke PlayAll.
                    </p>
                </div>

                <!-- Ganti dengan URL halaman tiket admin jika tersedia -->
                <a href="{{ url('/admin/reports') }}"
                    class="flex items-center gap-1 text-sm font-medium text-gray-700 hover:text-black">
                    Lihat semua
                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($reports->sortByDesc('created_at')->take(5) as $report)
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">

                        <div class="flex min-w-0 items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100">
                                <i data-lucide="file-text" class="h-5 w-5 text-gray-600"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold">
                                    {{ $report->ticket_code }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $report->user->name ?? 'User #' . $report->user_id }}
                                    · {{ $report->category_label }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $report->created_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <span class="rounded-full border border-gray-200 px-3 py-1 text-xs font-medium text-gray-600">
                            {{ $report->status === 'completed' ? 'Selesai' : 'Sedang Diproses' }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <i data-lucide="inbox" class="mx-auto h-8 w-8 text-gray-400"></i>
                        <p class="mt-3 text-sm font-medium">Belum ada laporan</p>
                        <p class="mt-1 text-xs text-gray-500">
                            Laporan terbaru akan muncul di sini.
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Pengguna Terbaru -->
        <section id="users" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
                <h2 class="font-bold">Pengguna Terbaru</h2>
                <p class="mt-1 text-xs text-gray-500">
                    Lima akun terakhir yang terdaftar.
                </p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($latestUsers as $user)
                    <div class="flex items-center gap-3 px-5 py-4 sm:px-6">

                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt=""
                                class="h-10 w-10 rounded-full border border-gray-200 object-cover">
                        @else
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-200 font-semibold text-gray-700">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold">{{ $user->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
                        </div>

                        <div class="hidden text-right sm:block">
                            <p class="text-xs text-gray-400">Bergabung</p>
                            <p class="mt-1 text-xs text-gray-600">
                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="p-8 text-center text-sm text-gray-500">
                        Belum ada pengguna.
                    </p>
                @endforelse
            </div>
        </section>

        <footer class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} PlayAll
        </footer>

    </main>

    <script>
        lucide.createIcons();

        // Loading halaman
        const pageLoader = document.getElementById('pageLoader');

        window.addEventListener('load', function() {
            pageLoader.classList.add('opacity-0', 'pointer-events-none');

            setTimeout(function() {
                pageLoader.classList.add('hidden');
            }, 300);
        });

        // Sidebar
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const sidebarButton = document.getElementById('sidebarButton');
        const closeButton = document.getElementById('closeSidebarButton');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        sidebarButton.addEventListener('click', openSidebar);
        closeButton.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Dropdown profil
        const menuButton = document.getElementById('menuButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const chevronIcon = document.getElementById('chevronIcon');

        menuButton.addEventListener('click', function(event) {
            event.stopPropagation();

            dropdownMenu.classList.toggle('hidden');

            const isOpen = !dropdownMenu.classList.contains('hidden');
            menuButton.setAttribute('aria-expanded', String(isOpen));
            chevronIcon.classList.toggle('rotate-180', isOpen);
        });

        document.addEventListener('click', function(event) {
            if (
                !dropdownMenu.contains(event.target) &&
                !menuButton.contains(event.target)
            ) {
                dropdownMenu.classList.add('hidden');
                menuButton.setAttribute('aria-expanded', 'false');
                chevronIcon.classList.remove('rotate-180');
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
                dropdownMenu.classList.add('hidden');
            }
        });

        // Pencarian user AJAX
        const searchInput = document.getElementById('searchInput');
        const searchDropdown = document.getElementById('searchDropdown');
        let searchTimeout;
        let searchController;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);

            if (searchController) {
                searchController.abort();
            }

            const query = this.value.trim();

            if (!query) {
                searchDropdown.classList.add('hidden');
                searchDropdown.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(async function() {
                searchController = new AbortController();

                searchDropdown.innerHTML =
                    '<p class="p-4 text-sm text-gray-500">Mencari user...</p>';
                searchDropdown.classList.remove('hidden');

                try {
                    const response = await fetch(
                        `{{ route('users.search') }}?q=${encodeURIComponent(query)}`, {
                            headers: {
                                'Accept': 'application/json'
                            },
                            signal: searchController.signal
                        }
                    );

                    if (!response.ok) throw new Error('Pencarian gagal');

                    const users = await response.json();

                    if (searchInput.value.trim() !== query) return;

                    searchDropdown.innerHTML = '';

                    if (!Array.isArray(users) || users.length === 0) {
                        searchDropdown.innerHTML =
                            '<p class="p-4 text-center text-sm text-gray-500">User tidak ditemukan.</p>';
                        return;
                    }

                    users.forEach(function(user) {
                        const link = document.createElement('a');
                        link.href = user.url;
                        link.className =
                            'flex items-center gap-3 border-b border-gray-100 px-4 py-3 hover:bg-gray-50';

                        const avatar = document.createElement('img');
                        avatar.src = user.avatar;
                        avatar.alt = '';
                        avatar.className =
                            'h-9 w-9 rounded-full border border-gray-200 object-cover';

                        const info = document.createElement('div');
                        info.className = 'min-w-0';

                        const name = document.createElement('p');
                        name.className = 'truncate text-sm font-semibold';
                        name.textContent = user.name;

                        const id = document.createElement('p');
                        id.className = 'text-xs text-gray-500';
                        id.textContent = `ID: ${user.id}`;

                        info.append(name, id);
                        link.append(avatar, info);
                        searchDropdown.appendChild(link);
                    });
                } catch (error) {
                    if (error.name === 'AbortError') return;

                    searchDropdown.innerHTML =
                        '<p class="p-4 text-sm text-gray-500">Pencarian gagal. Coba lagi.</p>';

                    console.error(error);
                }
            }, 300);
        });

        document.addEventListener('click', function(event) {
            if (
                !searchInput.contains(event.target) &&
                !searchDropdown.contains(event.target)
            ) {
                searchDropdown.classList.add('hidden');
            }
        });
    </script>

</body>

</html>