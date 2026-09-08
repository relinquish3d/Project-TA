<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white">
        <div class="w-full max-w-4xl h-[580px] bg-white border-4 border-blue-950 rounded-4xl grid grid-cols-2 overflow-hidden">

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

                {{-- Link SSO / Register --}}
                <p class="text-center text-sm text-gray-700 mt-4">
                    Login with SSO/
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline transition-colors duration-150">Create account</a>
                    @endif
                </p>

                {{-- Social Login --}}
                <div class="mt-4 space-y-3">
                    <button type="button"
                        class="w-full flex items-center gap-3 border border-gray-300 rounded-full px-4 py-2
                               transition-all duration-150 ease-out
                               hover:bg-gray-100 hover:-translate-y-0.5 active:scale-95 active:translate-y-0">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47a5.54 5.54 0 01-2.4 3.63v3.02h3.86c2.26-2.08 3.56-5.14 3.56-8.89z" />
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3.02c-1.07.72-2.45 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.96H1.28v3.11A11.997 11.997 0 0012 24z" />
                            <path fill="#FBBC05" d="M5.27 14.26A7.2 7.2 0 014.9 12c0-.78.14-1.55.37-2.26V6.63H1.28A11.997 11.997 0 000 12c0 1.93.46 3.76 1.28 5.37l3.99-3.11z" />
                            <path fill="#EA4335" d="M12 4.77c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.94 1.19 15.24 0 12 0 7.31 0 3.26 2.69 1.28 6.63l3.99 3.11C6.22 6.88 8.87 4.77 12 4.77z" />
                        </svg>
                        <span class="text-sm italic text-gray-700">Continue with Google</span>
                    </button>
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
                    <button href="/landingPage" class="flex-1 flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-48 h-48 object-contain">
                    </button>
                    {{-- Brand name --}}
                    <p class="text-2xl font-extrabold italic text-gray-900">PlayAll</p>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>