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

        <!-- Pop-up Titik Tiga (Dipaksa Presisi Pojok Kanan Atas Header) -->
        <div class="absolute right-6 top-3.5 z-40">
            <button id="menuButton" 
                    onclick="toggleMenu(event)" 
                    class="p-2 text-black hover:bg-gray-300 rounded-full transition focus:outline-none"
                    aria-label="Options">
                <i data-lucide="more-vertical" class="w-6 h-6"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu" 
                 class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-300 rounded-2xl shadow-lg z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-150 ease-out origin-top-right">
                
                <div class="py-1">
                    <!-- Opsi Report -->
                    <button type="button" 
                            onclick="openReportModal()" 
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                        <i data-lucide="flag" class="w-4 h-4 text-amber-500"></i>
                        <span>Report</span>
                    </button>

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
    </header>

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto px-6 py-8">
        
        <!-- Flash Alert Success -->
        @if (session('status') === 'profile-updated')
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex items-center justify-between">
                <span>Profile berhasil diperbarui!</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg">&times;</button>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left Column: Edit Form -->
                <div class="lg:col-span-6 space-y-6">
                    
                    <!-- Avatar Upload Section (Klik pada Foto) -->
                    <div class="flex items-center gap-6">
                        <label for="avatar" class="relative w-28 h-28 flex-shrink-0 cursor-pointer group rounded-full overflow-hidden">
                            <img id="avatarPreview" 
                                 src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="Profile Picture" 
                                 class="w-28 h-28 rounded-full object-cover border-2 border-gray-300 shadow-sm group-hover:opacity-75 transition">
                            
                            <!-- Overlay Ikon Kamera Saat Hover -->
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <i data-lucide="camera" class="w-8 h-8 text-white"></i>
                            </div>
                        </label>

                        <div>
                            <span class="block text-sm font-bold text-black">Photo Profile</span>
                            <p class="text-xs text-gray-500 mt-0.5">Klik pada foto untuk mengganti</p>
                            <p class="text-[11px] text-gray-400">JPG/PNG, Max 2MB</p>

                            <!-- Hidden File Input -->
                            <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewImage(event)">
                            
                            @error('avatar')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Full Name Field (Readonly) -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Full Name</label>
                        <input type="text" value="{{ $user->name }}" readonly
                            class="w-full bg-gray-300 border border-gray-400 rounded-full px-5 py-2.5 text-gray-600 cursor-not-allowed shadow-inner select-none">
                    </div>

                    <!-- Display Name Field -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Display Name</label>
                        <input type="text" name="display_name" value="{{ old('display_name', $user->display_name) }}"
                            placeholder="Masukkan display name..."
                            class="w-full bg-[#D9D9D9] border border-gray-400 rounded-full px-5 py-2.5 text-black focus:outline-none focus:border-black shadow-inner">
                        @error('display_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field (Readonly) -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Email</label>
                        <input type="email" value="{{ $user->email }}" readonly
                            class="w-full bg-gray-300 border border-gray-400 rounded-full px-5 py-2.5 text-gray-600 cursor-not-allowed shadow-inner select-none">
                    </div>

                    <!-- Bio Field -->
                    <div>
                        <label class="block text-sm font-bold italic text-black mb-1">Bio</label>
                        <textarea name="bio" rows="3" placeholder="Tuliskan bio singkat..."
                            class="w-full bg-[#D9D9D9] border border-gray-400 rounded-3xl px-5 py-3 text-black focus:outline-none focus:border-black shadow-inner resize-none">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <div class="pt-2 flex justify-center">
                        <button type="submit" class="bg-[#D9D9D9] border border-black text-black font-bold italic px-10 py-2 rounded-full hover:bg-black hover:text-white transition-all shadow-md">
                            Save
                        </button>
                    </div>

                </div>

                <!-- Right Column: Game Favorite & Ulasan -->
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

                    <!-- Ulasan User Card -->
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

    <!-- Modal Form Report -->
    <div id="reportModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl border border-gray-100 space-y-4">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-amber-500"></i>
                    Report User / Content
                </h3>
                <button type="button" onclick="closeReportModal()" class="text-gray-400 hover:text-black text-xl font-bold">&times;</button>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Pelaporan</label>
                <textarea rows="3" placeholder="Tuliskan alasan pelaporan Anda..." class="w-full p-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-black text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</button>
                <button type="button" onclick="submitReport()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">Kirim Laporkan</button>
            </div>
        </div>
    </div>

    <!-- Script Kontrol Popup & Preview Image -->
    <script>
        lucide.createIcons();

        // Preview foto profil
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

        // Toggle Popup Dropdown Menu
        function toggleMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('dropdownMenu');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                closeMenu();
            }
        }

        function closeMenu() {
            const menu = document.getElementById('dropdownMenu');
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.remove('opacity-100', 'scale-100');
                menu.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 150);
            }
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(event) {
            const menu = document.getElementById('dropdownMenu');
            const button = document.getElementById('menuButton');
            
            if (menu && button && !menu.contains(event.target) && !button.contains(event.target)) {
                closeMenu();
            }
        });

        // Modal Report functions
        function openReportModal() {
            closeMenu();
            document.getElementById('reportModal').classList.remove('hidden');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
        }

        function submitReport() {
            alert('Laporan Anda telah terkirim!');
            closeReportModal();
        }
    </script>
</body>
</html>