<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Teman Mabar - PlayAll</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F8F9FD] min-h-screen text-black antialiased selection:bg-[#4C49ED] selection:text-white">
    <!-- Loading Screen -->
    <div id="pageLoader"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white transition-opacity duration-300">
        <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="h-14 w-14 animate-pulse object-contain">
        <h2 class="mt-3 text-xl font-bold">PlayAll</h2>
        <p class="mt-2 text-sm text-gray-500">Memuat Teman Mabar...</p>
        <div class="mt-5 h-7 w-7 animate-spin rounded-full border-4 border-gray-200 border-t-black"></div>
    </div>

       {{--NAVBAR--}}
    <nav class="w-full fixed top-0 left-0 z-50 bg-[#D9D9D9] border-b border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] h-18">
        <div class="max-w-10xl mx-auto h-full px-6 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-5">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-9 h-9 object-contain transition-transform duration-200 group-hover:scale-105">
                    <span class="text-xl font-extrabold text-gray-900">
                        PlayAll
                    </span>
                </a>
            </div>

            <!-- Center Navigation Links -->
            <div class="w-full flex justify-center items-center gap-8">
                <!-- Dashboard (Active) -->
                <a href="{{ route('dashboard') }}" class="relative flex items-center gap-2 h-full text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colorsy">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Cari Teman -->
                <a href="{{ route('friends.index') }}" class="flex items-center gap-2 text-sm font-bold text-gray">
                    <i data-lucide="users" class="w-4 h-4 stroke-[2.5]"></i>
                    <span>Cari Teman</span>
                </a>

                <!-- Chat -->
                <a href="{{ route('chat.index') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    <span>Chat</span>
                </a>

                <!-- Game Favorit -->
                <a href="{{ url('/dashboard/#gameFavoriteGames') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">
                    <i data-lucide="gamepad-2" class="w-4 h-4"></i>
                    <span>Game Favorit</span>
                </a>
            </div>


                 

            </div>

        </div>
    </nav>

    <main class=" mx-auto px-6 pt-26 pb-16">
        
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
                                   class="w-full pl-9 pr-4 py-2 text-sm rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-black focus:bg-white transition">                      
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
                                        <a href="{{ route('profile.show', $gamer->id) }}" class="text-sm font-bold text-gray-900 hover:text-gray-500 transition truncate block">
                                            {{ $gamer->display_name ?? $gamer->name }}
                                        </a>
                                        <p class="text-xs text-gray-500 truncate">{{ $gamer->bio ?? 'Ready to play!' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-2 border-t border-gray-100/80">
                                    <a href="{{ route('profile.show', $gamer->id) }}" class="flex-1 py-1.5 px-3 text-center text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                        Profil
                                    </a>
                                   @if(in_array($gamer->id, $pendingUserIds))
                                {{-- Status Pending --}}
                                <button type="button" disabled
                                    class="flex-1 py-1.5 px-3 text-center text-xs font-semibold text-amber-700 bg-amber-100 border border-amber-200 rounded-lg flex items-center justify-center gap-1 cursor-not-allowed">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                 <span>Pending</span>
                                 </button>

@else
    {{-- Status Belum Add --}}
    <form action="{{ route('friends.add', $gamer->id) }}"
          method="POST" class="flex-1 m-0">

        @csrf

        <button type="submit"
            class="w-full py-1.5 px-3 text-center text-xs font-semibold
            text-black bg-[#D9D9D9] hover:bg-gray-200
            rounded-lg transition flex items-center justify-center gap-1">

            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
            <span>Add +</span>
        </button>
    </form>
@endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

{{-- Friend List Section --}}
<div class="bg-white rounded-2xl p-6 sm:p-7 border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)]">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                Friend List
            </h2>
            <p class="text-xs text-gray-500 mt-1">
                Daftar teman yang sudah terhubung denganmu.
            </p>
        </div>

        <span class="bg-indigo-50 text-black text-xs font-bold px-3 py-1 rounded-full">
            {{ $friends->count() }} Teman
        </span>
    </div>

    {{-- Jika Belum Memiliki Teman --}}
    @if($friends->isEmpty())

        <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
            <i data-lucide="users" class="w-9 h-9 text-gray-400 mx-auto mb-3"></i>

            <p class="text-sm font-semibold text-gray-700">
                Belum Ada Teman
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Mulai tambahkan gamer untuk membangun sirkel mabar kamu!
            </p>
        </div>

    @else

        {{-- Daftar Teman --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            @foreach($friends as $friend)

                <div class="p-4 rounded-xl border border-gray-100 bg-[#FAFAFE] hover:bg-white hover:border-indigo-100 hover:shadow-md transition-all">

                    {{-- Informasi Teman --}}
                    <div class="flex items-center gap-3">

                        @if($friend->avatar)
                            <img
                                src="{{ $friend->avatar_url }}"
                                alt="{{ $friend->name }}"
                                class="w-12 h-12 rounded-full object-cover border border-gray-200 shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-900 text-white font-bold flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($friend->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <a href="{{ route('profile.show', $friend->id) }}"
                                class="text-sm font-bold text-gray-900 hover:text-gray-500 transition truncate block">
                                {{ $friend->display_name ?? $friend->name }}
                            </a>

                            <p class="text-xs text-gray-500 truncate">
                                {{ $friend->bio ?? 'Teman mabar kamu' }}
                            </p>
                        </div>

                    </div>

                    {{-- Action --}}
                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100">

                        <a href="{{ route('profile.show', $friend->id) }}"
                            class="flex-1 py-2 text-center text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            Profil
                        </a>

                        <a href="{{ route('chat.index') }}"
                            class="flex-1 py-2 text-center text-xs font-semibold text-gray-500 bg-[#B7B09E] hover:bg-gray-200 rounded-lg transition flex items-center justify-center gap-1">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                            Chat
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>

            </div>
            

            <!-- Right Sidebar: Permintaan Pertemanan Masuk (Col Span 4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-2xl p-10 sm-p-5 border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)]">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                        <h2 class="text-base font-extrabold text-gray-900">Permintaan Masuk</h2>
                        <span id="requestCountBadge" class="bg-indigo-50 text-black text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $incomingRequests->count() }}
                        </span>
                    </div>

                    <div id="incomingRequestsContainer">
                        @if($incomingRequests->isEmpty())
                            <div class="p-6 text-center bg-white rounded-xl ">
                                <i data-lucide="inbox" class="w-6 h-10 text-gray-400 mx-auto mb-1.5"></i>
                                <p class="text-xs text-gray-500">Tidak ada permintaan pertemanan saat ini.</p>
                            </div>
                        @else
                            <div class="space-y-3" id="incomingRequestsList">
                                @foreach($incomingRequests as $req)
                                <div class="request-item p-3 rounded-xl bg-gray-50/70 border border-gray-100 flex items-center justify-between">
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
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-[#4C49ED] hover:bg-indigo-700 text-white flex items-center justify-center transition shadow-xs cursor-pointer" title="Accept">
                                                <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.remove', $req->id_invite ?? $req->id) }}" method="POST" class="inline m-0 form-decline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-[#FF1818] hover:bg-red-700 text-white flex items-center justify-center transition cursor-pointer" title="Decline">
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

        </div>

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

        // Konfigurasi SweetAlert2 dengan Tailwind CSS murni
        const swalConfig = {
            customClass: {
                popup: 'rounded-3xl p-6 sm:p-7 shadow-2xl border border-gray-100 font-sans max-w-sm sm:max-w-md bg-white',
                title: 'text-xl font-extrabold text-gray-900 tracking-tight',
                htmlContainer: 'text-sm text-gray-500 mt-2 leading-relaxed',
                actions: 'mt-6 gap-3 flex flex-row justify-center items-center',
                confirmButton: 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-red-600 hover:bg-red-700 shadow-sm transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-500/30',
                cancelButton: 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-bold text-xs text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all cursor-pointer focus:outline-none'
            },
            buttonsStyling: false
        };
        const playAllSwal = Swal.mixin(swalConfig);

        // Konfirmasi SweetAlert2 saat Menolak Permintaan Pertemanan
        document.querySelectorAll('.form-decline').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formEl = this;
                const requestItem = formEl.closest('.request-item');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                playAllSwal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(formEl.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: new FormData(formEl)
                        })
                        .then(response => {
                            playAllSwal.fire({
                                title: "Deleted!",
                                text: "Permintaan pertemanan berhasil ditolak.",
                                icon: "success",
                                confirmButtonText: "OK",
                                customClass: {
                                    ...swalConfig.customClass,
                                    confirmButton: 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-[#4C49ED] hover:bg-indigo-700 shadow-sm transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/30'
                                }
                            }).then(() => {
                                if (requestItem) {
                                    requestItem.style.transition = 'all 0.3s ease';
                                    requestItem.style.opacity = '0';
                                    requestItem.style.transform = 'translateX(20px)';
                                    setTimeout(() => {
                                        requestItem.remove();
                                        updateRequestBadge();
                                    }, 300);
                                } else {
                                    window.location.reload();
                                }
                            });
                        })
                        .catch(err => {
                            // Fallback jika fetch terhambat
                            formEl.submit();
                        });
                    }
                });
            });
        });

        function updateRequestBadge() {
            const badge = document.getElementById('requestCountBadge');
            const list = document.getElementById('incomingRequestsList');
            const container = document.getElementById('incomingRequestsContainer');
            if (list) {
                const remaining = list.querySelectorAll('.request-item').length;
                if (badge) {
                    badge.textContent = remaining;
                }
                if (remaining === 0 && container) {
                    container.innerHTML = `
                        <div class="p-6 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <i data-lucide="inbox" class="w-6 h-6 text-gray-400 mx-auto mb-1.5"></i>
                            <p class="text-xs text-gray-500">Tidak ada permintaan pertemanan saat ini.</p>
                        </div>
                    `;
                    lucide.createIcons();
                }
            }
        }
    </script>
</body>
</html>
