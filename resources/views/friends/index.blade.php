<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Teman Mabar - PlayAll</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F8F9FD] min-h-screen text-gray-800 antialiased selection:bg-[#4C49ED] selection:text-white">

    {{-- Top Navbar --}}
    <nav class="w-full fixed top-0 left-0 z-50 bg-white border-b border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] h-18">
        <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 transition mr-2">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-9 h-9 object-contain">
                    <span class="text-xl font-extrabold tracking-tight text-gray-900 group-hover:text-[#4C49ED] transition-colors">
                        PlayAll
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition">Dashboard</a>
                <a href="{{ route('friends.index') }}" class="text-sm font-bold text-[#4C49ED]">Cari Teman</a>
                <a href="{{ route('chat.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition">Chat</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pt-26 pb-16">
        
        <!-- Flash Alert -->
        @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 animate-in fade-in">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Temukan Teman Mabar</h1>
            <p class="text-sm text-gray-500 mt-1">Cari rekan bermain, kelola permintaan pertemanan, dan perluas sirkel bermainmu.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left / Main: Rekomendasi Teman (Col Span 8) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Rekomendasi Teman Section -->
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)]">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Rekomendasi Gamer</h2>
                            <p class="text-xs text-gray-500">Pemain aktif yang mungkin cocok dengan preferensi game kamu</p>
                        </div>
                        
                        <!-- Search Filter -->
                        <div class="relative w-full sm:w-64">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
                            <input type="text" id="filterInput" placeholder="Cari nama gamer..."
                                   class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-[#4C49ED] focus:bg-white transition">
                        </div>
                    </div>

                    @if($usersToRecommend->isEmpty())
                        <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <i data-lucide="users" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-sm font-semibold text-gray-700">Semua gamer sudah terhubung!</p>
                            <p class="text-xs text-gray-500 mt-1">Saat ini belum ada rekomendasi pemain baru.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="recommendGrid">
                            @foreach($usersToRecommend as $gamer)
                            <div class="gamer-card p-4 rounded-xl border border-gray-100 bg-[#FAFAFE] hover:bg-white hover:border-indigo-100 hover:shadow-md transition-all flex flex-col justify-between"
                                 data-name="{{ strtolower($gamer->display_name ?? $gamer->name) }}">
                                <div class="flex items-center gap-3 mb-3">
                                    @if($gamer->avatar)
                                        <img src="{{ $gamer->avatar_url }}" alt="{{ $gamer->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gray-900 text-white font-bold text-base flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($gamer->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="truncate">
                                        <a href="{{ route('profile.show', $gamer->id) }}" class="text-sm font-bold text-gray-900 hover:text-[#4C49ED] transition truncate block">
                                            {{ $gamer->display_name ?? $gamer->name }}
                                        </a>
                                        <p class="text-xs text-gray-500 truncate">{{ $gamer->bio ?? 'Ready to play!' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-2 border-t border-gray-100/80">
                                    <a href="{{ route('profile.show', $gamer->id) }}" class="flex-1 py-1.5 px-3 text-center text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                        Profil
                                    </a>
                                    <form action="{{ route('friends.add', $gamer->id) }}" method="POST" class="flex-1 m-0">
                                        @csrf
                                        <button type="submit" class="w-full py-1.5 px-3 text-center text-xs font-semibold text-white bg-[#4C49ED] hover:bg-indigo-700 rounded-lg transition shadow-xs flex items-center justify-center gap-1">
                                            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                                            <span>Add +</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Sidebar: Permintaan Pertemanan Masuk (Col Span 4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)]">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                        <h2 class="text-base font-extrabold text-gray-900">Permintaan Masuk</h2>
                        <span class="bg-indigo-50 text-[#4C49ED] text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $incomingRequests->count() }}
                        </span>
                    </div>

                    @if($incomingRequests->isEmpty())
                        <div class="p-6 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <i data-lucide="inbox" class="w-6 h-6 text-gray-400 mx-auto mb-1.5"></i>
                            <p class="text-xs text-gray-500">Tidak ada permintaan pertemanan saat ini.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($incomingRequests as $req)
                            <div class="p-3 rounded-xl bg-gray-50/70 border border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    @if($req->sender && $req->sender->avatar)
                                        <img src="{{ $req->sender->avatar_url }}" alt="{{ $req->sender->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-900 text-white font-bold text-sm flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($req->sender->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="truncate">
                                        <h4 class="text-sm font-bold text-gray-900 truncate">
                                            {{ $req->sender->display_name ?? $req->sender->name }}
                                        </h4>
                                        <p class="text-[11px] text-gray-400">{{ $req->created_at ? $req->created_at->diffForHumans() : 'Baru saja' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                    <form action="{{ route('friends.accept', $req->id_invite ?? $req->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-[#4C49ED] hover:bg-indigo-700 text-white flex items-center justify-center transition shadow-xs" title="Accept">
                                            <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('friends.remove', $req->id_invite ?? $req->id) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-[#FF1818] border border-black-200 hover:bg-red-700 text-white flex items-center justify-center transition" title="Decline">
                                            <i data-lucide="x" class="w-4 h-4 stroke-[2.5]"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </main>

    <script>
        lucide.createIcons();

        // Search Filter for Recommendations
        const filterInput = document.getElementById('filterInput');
        if (filterInput) {
            filterInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.gamer-card').forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    if (name.includes(q)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    </script>
</body>
</html>
