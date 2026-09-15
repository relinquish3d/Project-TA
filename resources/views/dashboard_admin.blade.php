

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Telah Datang</title>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
        </span>
        <input type="text" class="w-64 rounded-full pl-10 pr-4 py-1.5 text-sm bg-transparent border border-gray-400 text-black placeholder-gray-500 focus:outline-none focus:border-black" placeholder="Cari User...">
    </div>

    <!-- Navigation Links -->
    <div class="flex items-center gap-8">
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
            <a class="text-black font-bold text-sm hover:underline transition-colors" href="{{ route('register') }}">Sign Up</a>
            <a class="text-black font-bold text-sm hover:underline transition-colors" href="{{ route('login') }}">Login</a>
        @endauth
    </div>
  </nav>
  
    <h1>HALO ADMIN SELAMAT DATANG!!!!!</h1>
                        <p class="mt-2">
                        Halo, {{ auth()->user()->name }}
                    </p>
</body>
</html>