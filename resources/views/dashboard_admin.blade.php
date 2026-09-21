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
        
        <div class="absolute right-6 top-3.5 z-40">
            <button id="menuButton" 
                    onclick="toggleMenu(event)" 
                    class="p-2 text-black hover:bg-gray-300 rounded-full transition focus:outline-none"
                    aria-label="Options">
                <i data-lucide="more-vertical" class="w-6 h-5"></i>
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
    </nav>
    <p class="">
        Halo, {{ auth()->user()->name }}
    </p>
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