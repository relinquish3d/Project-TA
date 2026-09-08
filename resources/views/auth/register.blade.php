<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white">
        <div class="w-full max-w-4xl h-[580px] bg-white border-4 border-blue-950 rounded-4xl grid grid-cols-2 overflow-hidden shadow-2xl">

            {{-- Kolom Kiri: Logo --}}
            <div class="min-w-0 h-full rounded-3xl bg-gray-300 p-6 flex items-center justify-center">
                <div class="w-full h-full bg-gray-200 rounded-4xl border border-gray-400 flex flex-col items-center justify-between py-6">

                    {{-- Notch / status bar --}}
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-2 bg-gray-100 rounded-full"></span>
                        <span class="w-10 h-2 bg-black rounded-full"></span>
                        <span class="w-6 h-2 bg-gray-100 rounded-full"></span>
                    </div>

                    {{-- Logo --}}
                    <div class="flex-1 flex items-center justify-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="PlayAll" class="w-[300px] h-[400px] object-contain hover:blur-sm hover:animate-spin hover:transition-20 hover:duration-500">
                        </a>
                    </div>

                    {{-- Brand name --}}
                    <p class="text-2xl font-extrabold italic text-gray-900">PlayAll</p>
                </div>
            </div>

            {{-- Kolom Kanan: Form Register --}}
            <div class="min-w-0 h-full p-8 flex flex-col justify-center overflow-y-auto">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Create your account</h1>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">Full name</label>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-2 bg-white
                        transition-all duration-200 ease-out
                                    focus-within:border-blue-950 focus-within:ring-2 focus-within:ring-blue-950/10 focus-within:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                autocomplete="name" placeholder="Add name" style="color-scheme: light"
                                class="h-7 w-100 focus:outline-hidden border-0 focus:ring-0 p-0 text-sm bg-white text-gray-900 italic placeholder-gray-400">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">Email</label>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-2 bg-white
                        transition-all duration-200 ease-out
                                    focus-within:border-blue-950 focus-within:ring-2 focus-within:ring-blue-950/10 focus-within:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autocomplete="username" placeholder="Example@example.com" style="color-scheme: light"
                                class="h-7 w-100 focus:outline-hidden border-0 focus:ring-0 p-0 text-sm bg-white text-gray-900 italic placeholder-gray-400">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-800 mb-1">Password</label>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-2 bg-white
                        transition-all duration-200 ease-out
                                    focus-within:border-blue-950 focus-within:ring-2 focus-within:ring-blue-950/10 focus-within:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input id="password" type="password" name="password" required
                                autocomplete="new-password" placeholder="********" style="color-scheme: light"
                                class="h-7 w-100 focus:outline-hidden border-0 focus:ring-0 p-0 text-sm bg-white text-gray-900 placeholder-gray-400">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-800 mb-1">Confirm password</label>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-full px-4 py-2 bg-white
                        transition-all duration-200 ease-out
                                    focus-within:border-blue-950 focus-within:ring-2 focus-within:ring-blue-950/10 focus-within:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password" placeholder="********" style="color-scheme: light"
                                class="h-7 w-100 focus:outline-hidden border-0 focus:ring-0 p-0 text-sm bg-white text-gray-900 placeholder-gray-400">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    {{-- Tombol Next --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full border border-gray-300 rounded-full py-2 text-sm text-gray-700 hover:bg-gray-100 transition
                            transition-all duration-150 ease-out
                                   hover:bg-gray-100 hover:-translate-y-0.5 active:scale-95 active:translate-y-0">
                            {{ __('Next') }}
                        </button>
                    </div>
                </form>

                {{-- Link Login --}}
                <p class="text-center text-sm text-gray-700 mt-4">
                    Login with SSO/
                    <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">{{ __('Login') }}</a>
                </p>
            </div>

        </div>
    </div>
</x-guest-layout>