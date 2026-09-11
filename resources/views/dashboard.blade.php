<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PlayAll - Temukan Teman Mabar</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFCF8]">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
  
  {{-- Navbar --}}
  <nav class="w-full fixed top-0 z-50 flex flex-row h-16 px-6 bg-[#D9D9D9] border-b border-gray-300 items-center justify-between shadow-xs">
    <!-- Logo & Brand -->
    <div class="flex items-center gap-3">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-9 h-9 object-contain">
        </a>
        <span class="text-xl font-bold tracking-tight text-black">
            PlayAll
        </span>
    </div>

    <!-- Search Bar -->
    <div class="relative flex items-center">
        <span class="absolute left-3.5 text-gray-500 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
        </span>
        <input type="text" class="w-64 rounded-full pl-10 pr-4 py-1.5 text-sm bg-transparent border border-gray-400 text-black placeholder-gray-500 focus:outline-none focus:border-black" placeholder="Cari teman...">
    </div>

    <!-- Navigation Links -->
    <div class="flex items-center gap-8">
        <a class="text-gray-800 hover:text-black text-sm font-medium transition-colors" href="{{ route('chat.show') }}">Chat</a>
        <a class="text-gray-800 hover:text-black text-sm font-medium transition-colors" href="#gameFavorite">Game Favorit</a>
        <a class="text-gray-800 hover:text-black text-sm font-medium transition-colors" href="{{ url('/') }}">Home</a>
    </div>

    <!-- Auth Actions / Profile Photo -->
    <div class="flex items-center gap-6">
        @auth
            {{-- Foto Profil User jika sudah login --}}
            <a href="{{ route('profile.edit') }}" class="group relative flex items-center justify-center">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-400 group-hover:border-black transition-all shadow-xs">
                @else
                    {{-- Avatar Default jika user belum upload foto --}}
                    <div class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold text-sm border-2 border-gray-500 group-hover:border-black transition-all shadow-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </a>
        @else
            {{-- Tampilan jika belum login --}}
            <a class="text-black font-bold text-sm hover:underline transition-colors" href="{{ route('register') }}">Sign In</a>
            <a class="text-black font-bold text-sm hover:underline transition-colors" href="{{ route('login') }}">Login</a>
        @endauth
    </div>
  </nav>

  {{-- Hero Section --}}
  <div class="flex flex-row items-center justify-center flex-col gap-100 mt-55 mb-60 p-8 h-fill bg-[#FDFCF8]">
    <div class=" order-1 flex flex-col w-100">
      <h1 class=" text-5xl w-100 font-bold text-4xl">Temukan Teman Mabar Mu Di Sini</h1>
      <br>
      <p class="rounded-5xl text-md font-light flex-col">
      Bosan bermain sendiri, ingin mendapatkan teman mabar?
      Di sini kamu dapat menemukan teman mabar yang sesuai
      dengan game yang kamu mainkan
      </p>
    </div>
    <div class="order-2 flex flex-row items-center justify-right gap-4">
     <img style="border-radius: 7rem;" src="{{ asset('images/game.png') }}" class="drop-shadow-[-18px_10px_3px_#A6A6A6] w-130  block" alt="bayangan kiri">
    </div>
  </div>

  {{-- Game Favorite Section --}}
  <section id="gameFavorite" class="pt-20">
    <!-- Bagian Judul -->
    <div class="flex flex-col items-center justify-center gap-1 p-8 bg-[#FDFCF8]">
        <h1 class="text-3xl font-bold">Pilih Game Favoritmu</h1>
        <br>
        <p>MABAR mendukung empat game kompetitif terbesar saat ini. Temukan partner bermain</p>
        <p>untuk setiap platform favoritmu.</p>
    </div>

    <!-- Container Pembungkus Card (Berjejer 4 Game di Tengah dengan Efek Hover) -->
    <div class="max-w-8xl mx-auto flex flex-wrap justify-center gap-10 p-20 bg-[#FDFCF8]">

        <!-- Card 1: Mobile Legends -->
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#">
                <img class="w-full h-auto" src="{{ asset('images/Game/ML.png') }}" alt="Mobile Legends" />
            </a>
            <div class="p-4 text-left">
                <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                    <span>MOBA 5V5</span>
                    <span>Ranked / Classic</span>
                </div>
                <a href="#">
                    <h5 class="text-base font-bold tracking-tight text-black">Mobile Legends</h5>
                </a>
            </div>
        </div>

        <!-- Card 2: PUBG Mobile -->
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#">
                <img class="w-full h-auto" src="{{ asset('images/Game/pubg.png') }}" alt="PUBG Mobile" />
            </a>
            <div class="p-4 text-left">
                <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                    <span>BATTLE ROYALE</span>
                    <span>Squad / Duo</span>
                </div>
                <a href="#">
                    <h5 class="text-base font-bold tracking-tight text-black">PUBG Mobile</h5>
                </a>
            </div>
        </div>

        <!-- Card 3: Valorant -->
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#">
                <img class="w-full h-auto" src="{{ asset('images/Game/valorant.png') }}" alt="Valorant" />
            </a>
            <div class="p-4 text-left">
                <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                    <span>TACTICAL SHOOTER</span>
                    <span>Competitive 5v5</span>
                </div>
                <a href="#">
                    <h5 class="text-base font-bold tracking-tight text-black">Valorant</h5>
                </a>
            </div>
        </div>

        <!-- Card 4: FC Mobile -->
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#">
                <img class="w-full h-auto" src="{{ asset('images/Game/FC.png') }}" alt="FC Mobile" />
            </a>
            <div class="p-4 text-left">
                <div class="flex justify-between items-center text-[10px] text-gray-600 font-semibold mb-1">
                    <span>SPORTS SIMULATION</span>
                    <span>1v1 Head-to-Head</span>
                </div>
                <a href="#">
                    <h5 class="text-base font-bold tracking-tight text-black">FC Mobile</h5>
                </a>
            </div>
        </div>

    </div>
  </section>

  {{-- Section Mobile Legends Players --}}
  <div class="bg-white p-8 font-sans items-center justify-center flex flex-col gap-6">
    <h1 class="text-2xl flex justify-start w-full font-bold text-black mt-20 px-9 pl-35">Mobile Legends</h1>
    <div class="max-w-7xl mx-auto flex flex-wrap justify-center gap-10 p-6">
        {{-- Card Songmin --}}
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Songmin.png') }}" alt="Songmin" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Songmin</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        {{-- Card Michh --}}
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Michh.png') }}" alt="Michh" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Michh</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        {{-- Card Lizz --}}
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Lizz.png') }}" alt="Lizz" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Lizz</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        {{-- Card Asa --}}
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Asa.png') }}" alt="Asa" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Asa</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
    </div>
  </div>

  {{-- Section PUBG Players --}}
  <div class="bg-white p-8 font-sans items-center justify-center flex flex-col gap-6">
    <h1 class="text-2xl flex justify-start w-full font-bold text-black mt-20 px-9 pl-35">PUBG</h1>
    <div class="max-w-7xl mx-auto flex flex-wrap justify-center gap-10 p-6">
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Songmin.png') }}" alt="Songmin" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Songmin</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Michh.png') }}" alt="Michh" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Michh</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Lizz.png') }}" alt="Lizz" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Lizz</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Asa.png') }}" alt="Asa" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Asa</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
    </div>
  </div>

  {{-- Section Valorant Players --}}
  <div class="bg-white p-8 font-sans items-center justify-center flex flex-col gap-6">
    <h1 class="text-2xl flex justify-start w-full font-bold text-black mt-20 px-9 pl-35">Valorant</h1>
    <div class="max-w-7xl mx-auto flex flex-wrap justify-center gap-10 p-6">
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Songmin.png') }}" alt="Songmin" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Songmin</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Michh.png') }}" alt="Michh" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Michh</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Lizz.png') }}" alt="Lizz" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Lizz</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Asa.png') }}" alt="Asa" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Asa</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
    </div>
  </div>

  {{-- Section FC Mobile Players --}}
  <div class="bg-white p-8 font-sans items-center justify-center flex flex-col gap-6">
    <h1 class="text-2xl flex justify-start w-full font-bold text-black mt-20 px-9 pl-35">FC Mobile</h1>
    <div class="max-w-7xl mx-auto flex flex-wrap justify-center gap-10 p-6">
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Songmin.png') }}" alt="Songmin" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Songmin</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Michh.png') }}" alt="Michh" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Michh</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Lizz.png') }}" alt="Lizz" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Lizz</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
        <div class="bg-[#D9D9D9] block w-66 border border-black rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
            <a href="#"><img class="w-full h-auto object-cover" src="{{ asset('images/Pemain/Asa.png') }}" alt="Asa" /></a>
            <div class="p-4 text-left">
                <div class="flex text-[#fbbf24] text-lg gap-0.5 mb-1"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                <h5 class="text-base font-bold tracking-tight text-black mb-3">Asa</h5>
                <a href="#" class="block text-center text-white bg-[#22c55e] hover:bg-[#16a34a] font-medium text-sm py-2 rounded-full transition-colors duration-200">Add +</a>
            </div>
        </div>
    </div>
  </div>

  {{-- Footer Section --}}
  <footer class="bg-[#E5E5E5] text-black py-12 px-6 md:px-16 lg:px-24">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-start gap-10">
        <!-- Bagian Logo & Nama Brand -->
        <div class="flex items-center gap-5">
            <img src="{{ asset('images/logo.png') }}" alt="PlayAll Logo" class="w-35 h-35 object-contain" />
            <span class="text-2xl font-bold tracking-tight text-black">PlayAll</span>
        </div>

        <!-- Bagian Menu Navigasi & Account -->
        <div class="flex md:gap-32">
            <!-- Column 1: Navigation -->
            <div>
                <h3 class="font-bold text-lg mb-4 text-black">Navigation</h3>
                <ul class="space-y-3 text-sm text-gray-800">
                    <li><a href="{{ route('chat.show') }}" class="hover:text-black transition-colors">Chat</a></li>
                    <li><a href="#gameFavorite" class="hover:text-black transition-colors">Game Favorite</a></li>
                    <li><a href="{{ url('/') }}" class="hover:text-black transition-colors">Home</a></li>
                </ul>
            </div>

            <!-- Column 2: Account -->
            <div>
                <h3 class="font-bold text-lg mb-4 text-black">Account</h3>
                <ul class="space-y-3 text-sm text-gray-800">
                    @auth
                        <li><a href="{{ route('profile.edit') }}" class="hover:text-black transition-colors">Profile</a></li>
                    @else
                        <li><a href="{{ route('register') }}" class="hover:text-black transition-colors">Sign in</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-black transition-colors">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
  </footer>

</body>
</html>