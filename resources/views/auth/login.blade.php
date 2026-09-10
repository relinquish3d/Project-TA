<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white">
        <div class="w-full max-w-4xl h-[580px] bg-white border-4 border-blue-950 rounded-4xl grid grid-cols-2 overflow-hidden shadow-2xl">

            {{-- Kolom Kiri: Logo --}}

            {{-- Kolom Kiri: Form Login --}}
            <div class="min-w-0 h-full p-8 flex flex-col justify-center overflow-y-auto">
                <h1 class="text-4xl font-bold   text-gray-900">Welcome Back</h1>
                <p class="text-md italic text-gray-500 mt-1 mb-6">Sign in to continue to your account.</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">Email</label>
                        <div class="group flex items-center gap-2 border border-gray-300 rounded-full px-4 py-2 bg-white
                                    transition-all duration-200 ease-out
                                    focus-within:border-blue-950 focus-within:ring-2 focus-within:ring-blue-950/10 focus-within:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 shrink-0 transition-colors duration-200 group-focus-within:text-blue-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="Contoh@gmail.com" style="color-scheme: light"
                                class="h-7 w-100 focus:outline-hidden p-0 text-sm bg-white text-gray-900 italic placeholder-gray-400" rounded-full>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-800 mb-1">Password</label>
                        <div class="group flex items-center gap-2 border border-gray-300 rounded-full px-4 py-2 bg-white
                                    transition-all duration-200 ease-out
                                    focus-within:border-blue-950 focus-within:ring-2 focus-within:ring-blue-950/10 focus-within:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 shrink-0 transition-colors duration-200 group-focus-within:text-blue-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="********" style="color-scheme: light"
                                class="h-7 w-100 focus:outline-hidden border-0 focus:ring-0 p-0 text-sm bg-white text-gray-900 placeholder-gray-400">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Tombol Next --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full border border-gray-300 rounded-full py-2 text-sm text-gray-700
                                   transition-all duration-150 ease-out
                                   hover:bg-gray-100 hover:-translate-y-0.5 active:scale-95 active:translate-y-0">
                            {{ __('Next') }}
                        </button>
                    </div>
                </form>

                <p class="text-center text-sm text-gray-700 mt-4">
                    Login with SSO/
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline transition-colors duration-150">Create account</a>
                    @endif
                </p>

                {{-- Social Login --}}
                <div class="mt-4 space-y-3">
                        <a href="{{ url('auth/google') }}">
                    <button type="button"
                        class="w-full flex items-center gap-3 border border-gray-300 rounded-full px-4 py-2
                               transition-all duration-150 ease-out
                               hover:bg-gray-100 hover:-translate-y-0.5 active:scale-95 active:translate-y-0">
                               <img src="{{ asset('images/Google_picture.png') }}" alt="Google Logo" class=" bg-gray-100 w-8 h-8 rounded-full object-contain"> 
                        <span class="text-sm italic text-gray-700">Continue with Google</span>
                    </button>
                        </a>
                </div>
            </div>

            {{-- Kolom Kanan: Mockup HP dengan Logo --}}
            <div class="min-w-0 h-full rounded-3xl bg-gray-300 p-6 flex items-center justify-center">
                <div class="w-full h-full bg-gray-200 rounded-3xl border border-gray-400 flex flex-col items-center justify-between py-6">

                    {{-- Notch / status bar --}}
                    <div class="flex items-center gap-2">
                        <span class="w-10 h-2 bg-black rounded-full"></span>
                        <span class="w-6 h-2 bg-gray-100 rounded-full"></span>
                        <span class="w-6 h-2 bg-gray-100 rounded-full"></span>
                    </div>

                    {{-- Logo --}}
                    <div class="flex-1 flex items-center justify-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-[300px] h-[300px] object-contain hover:blur-sm hover:animate-spin hover:transition-20 hover:duration-500">
                        </a>
                    </div>
                    {{-- Brand name --}}
                    <p class="text-2xl font-extrabold italic text-gray-900">PlayAll</p>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>