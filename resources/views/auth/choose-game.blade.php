<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white">
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

                <form method="POST" action="{{ route('register.complete') }}" class="w-full flex flex-col items-center">
                    @csrf

                    {{-- Grid Pilihan Game --}}
                    <div class="grid grid-cols-2 gap-5 mb-8 max-w-md w-full">
                        {{-- Logika Cadangan: Jika database kosong, pakai list game default ini --}}
                        @php
                            $displayCategories = $categories->isNotEmpty() ? $categories : collect([
                                (object)['game_name' => 'Valorant', 'image' => 'images/Game/valorant.png'],
                                (object)['game_name' => 'PUBG Mobile', 'image' => 'images/Game/pubg.png'],
                                (object)['game_name' => 'Mobile Legends', 'image' => 'images/Game/ML.png'],
                                (object)['game_name' => 'FC Mobile', 'image' => 'images/Game/FC.png']
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
                                <input type="checkbox" name="games[]" value="{{ $gameTitle }}" class="peer hidden" {{ in_array($gameTitle, old('games', [])) ? 'checked' : '' }}>
                                <div class="bg-[#E2E8F0] border-2 border-[#94A3B8] rounded-2xl p-4 flex flex-col items-center justify-center h-48 transition-all peer-checked:border-blue-600 peer-checked:ring-4 peer-checked:ring-blue-500/30 peer-checked:bg-white group-hover:border-blue-400">
                                    <div class="w-28 h-28 rounded-2xl overflow-hidden mb-3">
                                        <img class="w-full h-full object-cover" src="{{ asset($image) }}" alt="{{ $gameTitle }}" />  
                                    </div>
                                    <span class="text-base font-bold text-slate-900 text-center leading-tight">{{ $gameTitle }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Pesan error jika user belum memilih minimal 1 game --}}
                    @error('games')
                        <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                    @enderror

                    {{-- Tombol Next --}}
                    <button type="submit" class="w-48 bg-[#D1D5DB] border-2 border-[#9CA3AF] rounded-full py-2.5 text-base font-extrabold text-slate-900 hover:bg-gray-400 transition-all shadow-sm">
                        Next
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>
