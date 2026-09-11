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
                        
                        {{-- PUBG --}}
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="game" value="PUBG" class="peer hidden" required>
                            <div class="bg-[#E2E8F0] border-2 border-[#94A3B8] rounded-2xl p-4 flex flex-col items-center justify-center h-48 transition-all peer-checked:border-blue-600 peer-checked:ring-4 peer-checked:ring-blue-500/30 peer-checked:bg-white group-hover:border-blue-400">
                                <div class="w-28 h-28 rounded-2xl overflow-hidden mb-3">
                                    <img class="w-full h-full object-cover" src="{{ asset('images/Game/pubg.png') }}" alt="PUBG Mobile" />  
                                </div>
                                <span class="text-base font-bold text-slate-900">PUBG</span>
                            </div>
                        </label>

                        {{-- Valorant --}}
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="game" value="Valorant" class="peer hidden">
                            <div class="bg-[#E2E8F0] border-2 border-[#94A3B8] rounded-2xl p-4 flex flex-col items-center justify-center h-48 transition-all peer-checked:border-blue-600 peer-checked:ring-4 peer-checked:ring-blue-500/30 peer-checked:bg-white group-hover:border-blue-400">
                                <div class="w-28 h-28 rounded-2xl overflow-hidden mb-3">
                                    <img class="w-full h-full object-cover" src="{{ asset('images/Game/valorant.png') }}" alt="Valorant" />
                                </div>
                                <span class="text-base font-bold text-slate-900">Valorant</span>
                            </div>
                        </label>

                        {{-- Mobile Legends --}}
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="game" value="Mobile Legends" class="peer hidden">
                            <div class="bg-[#E2E8F0] border-2 border-[#94A3B8] rounded-2xl p-4 flex flex-col items-center justify-center h-48 transition-all peer-checked:border-blue-600 peer-checked:ring-4 peer-checked:ring-blue-500/30 peer-checked:bg-white group-hover:border-blue-400">
                                <div class="w-28 h-28 rounded-2xl overflow-hidden mb-3">
                                    <img class="w-full h-full object-cover" src="{{ asset('images/Game/ML.png') }}" alt="Mobile Legends" />
                                </div>
                                <span class="text-base font-bold text-slate-900 text-center leading-tight">Mobile Legends</span>
                            </div>
                        </label>

                        {{-- FC Mobile --}}
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="game" value="FC Mobile" class="peer hidden">
                            <div class="bg-[#E2E8F0] border-2 border-[#94A3B8] rounded-2xl p-4 flex flex-col items-center justify-center h-48 transition-all peer-checked:border-blue-600 peer-checked:ring-4 peer-checked:ring-blue-500/30 peer-checked:bg-white group-hover:border-blue-400">
                                <div class="w-28 h-28 rounded-2xl overflow-hidden mb-3">
                                    <img class="w-full h-full object-cover" src="{{ asset('images/Game/FC.png') }}" alt="FC Mobile" />
                                </div>
                                <span class="text-base font-bold text-slate-900">FC Mobile</span>
                            </div>
                        </label>

                    </div>

                    {{-- Tombol Next --}}
                    <button type="submit" class="w-48 bg-[#D1D5DB] border-2 border-[#9CA3AF] rounded-full py-2.5 text-base font-extrabold text-slate-900 hover:bg-gray-400 transition-all shadow-sm">
                        Next
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>