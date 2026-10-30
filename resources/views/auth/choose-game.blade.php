<x-guest-layout>
    {{-- Pembungkus utama dengan inisialisasi state Alpine.js --}}
    <div class="min-h-screen flex items-center justify-center bg-white relative overflow-x-hidden"
         x-data="{ 
            showAlert: false, 
            alertMessage: '', 
            alertType: 'error',
            checkedGames: {{ json_encode(old('games', [])) }},
            handleSubmit(event) {
                if (this.checkedGames.length === 0) {
                    this.alertType = 'error';
                    this.alertMessage = 'Harus pilih salah satu game!';
                    this.showAlert = true;
                    setTimeout(() => this.showAlert = false, 4000);
                } else {
                    this.alertType = 'success';
                    this.alertMessage = 'Pilihan game berhasil disimpan! Menuju Dashboard...';
                    this.showAlert = true;
                    setTimeout(() => {
                        event.target.submit();
                    }, 1500);
                }
            }
         }">

        {{-- 🚨 ELEMEN POP-UP DUAL MODE: UKURAN AGAK DIBESARKAN & LEBIH TEBAL --}}
        <div x-show="showAlert" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="fixed top-6 right-6 z-50 bg-zinc-900/85 backdrop-blur-md border border-zinc-700/40 rounded-full px-5 py-2.5 shadow-lg flex items-center gap-4"
             x-cloak>
            <div class="flex items-center gap-2">
                <div class="flex-shrink-0">
                    <!-- Ikon Centang Lembut untuk Sukses -->
                    <svg x-show="alertType === 'success'" class="w-4.5 h-4.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75 10.336 18.5 19.5 5.5" />
                    </svg>
                    <!-- Ikon Tanda Seru Lembut untuk Error -->
                    <svg x-show="alertType === 'error'" class="w-4.5 h-4.5 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.959 0 0 0 12 21a11.959 11.959 0 0 0 8.402-15A11.959 11.959 0 0 1 12 2.714Z" />
                    </svg>
                </div>
                <span class="text-sm font-bold text-zinc-100 tracking-wide whitespace-nowrap" x-text="alertMessage"></span>
            </div>
            <button type="button" @click="showAlert = false" class="text-zinc-400 hover:text-white text-lg leading-none font-medium px-1 cursor-pointer">&times;</button>
        </div>

        {{-- BENTUK ASLI: Bingkai Utama Tidak Diubah Sedikit Pun --}}
        <div class="w-full max-w-4xl h-[580px] bg-white border-4 border-blue-950 rounded-4xl grid grid-cols-2 overflow-hidden shadow-2xl">

            {{-- Kolom Kiri: Logo --}}
            <div class="min-w-0 h-full rounded-3xl bg-gray-300 p-6 flex items-center justify-center">
                <div class="w-full h-full bg-gray-200 rounded-4xl border border-gray-400 flex flex-col items-center justify-between py-6">
                    <div class="flex items-center gap-2">
                        <span class="w-10 h-2 bg-black rounded-full"></span>
                        <span class="w-10 h-2 bg-black rounded-full"></span>
                        <span class="w-10 h-2 bg-black rounded-full"></span>
                    </div>

                    <div class="flex-1 flex items-center justify-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="PlayAll">
                        </a>
                    </div>

                    <p class="text-2xl font-extrabold italic text-gray-900">PlayAll</p>
                </div>
            </div>

            {{-- Kolom Kanan: Choose Your Game --}}
            <div class="min-w-0 h-full p-8 flex flex-col items-center justify-center overflow-y-auto">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">Choose your game</h1>

                {{-- Mencegah submit default dan memprosesnya lewat fungsi handleSubmit --}}
                <form method="POST" action="{{ route('register.complete') }}" 
                      @submit.prevent="handleSubmit(event)"
                      class="w-full flex flex-col items-center">
                    @csrf

                    {{-- Grid Pilihan Game (Bentuk Asli) --}}
                    <div class="grid grid-cols-2 gap-5 mb-8 max-w-md w-full">
                        @php
                            $displayCategories = $categories->isNotEmpty() ? $categories : collect([
                                (object)['game_name' => 'Valorant', 'image' => 'images/Game/valorant.png'],
                                (object)['game_name' => 'PUBG Mobile', 'image' => 'images/Game/pubg.png'],
                                (object)['game_name' => 'Mobile Legends', 'image' => 'images/Game/ML.png'],
                                (object)['game_name' => 'EA SPORTS FC', 'image' => 'images/Game/FC.png']
                            ]);
                        @endphp

                        @foreach ($displayCategories as $cat)
                            @php
                                $gameTitle = $cat->game_name;
                                $image = 'images/Game/valorant.png';
                                if (stripos($gameTitle, 'PUBG') !== false) {
                                    $image = 'images/Game/pubg.png';
                                } elseif (stripos($gameTitle, 'Mobile Legends') !== false || stripos($gameTitle, 'ML') !== false) {
                                    $image = 'images/Game/ML.png';
                                } elseif (stripos($gameTitle, 'FC') !== false) {
                                    $image = 'images/Game/FC.png';
                                } elseif (stripos($gameTitle, 'Valorant') !== false) {
                                    $image = 'images/Game/valorant.png';
                                } elseif (!empty($cat->image)) {
                                    $image = $cat->image;
                                }
                            @endphp
                            <label class="cursor-pointer group relative">
                                <input type="checkbox" name="games[]" value="{{ $gameTitle }}" 
                                       x-model="checkedGames"
                                       class="peer hidden" 
                                       {{ in_array($gameTitle, old('games', [])) ? 'checked' : '' }}>
                                <div class="bg-[#E2E8F0] border-2 border-[#94A3B8] rounded-2xl p-4 flex flex-col items-center justify-center h-48 transition-all peer-checked:border-blue-600 peer-checked:ring-4 peer-checked:ring-blue-500/30 peer-checked:bg-white group-hover:border-blue-400">
                                    <div class="w-28 h-28 rounded-2xl overflow-hidden mb-3">
                                        <img class="w-full h-full object-cover" src="{{ asset($image) }}" alt="{{ $gameTitle }}" />  
                                    </div>
                                    <span class="text-base font-bold text-slate-900 text-center leading-tight">{{ $gameTitle }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Tombol Next (Bentuk Asli) --}}
                    <button type="submit" class="w-48 bg-[#D1D5DB] border-2 border-[#9CA3AF] rounded-full py-2.5 text-base font-extrabold text-slate-900 hover:bg-gray-400 transition-all shadow-sm cursor-pointer">
                        Next
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>
