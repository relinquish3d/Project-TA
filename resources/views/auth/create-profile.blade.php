<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white">
        <div class="w-full max-w-4xl h-[580px] bg-white border-4 border-blue-950 rounded-4xl grid grid-cols-2 overflow-hidden shadow-2xl">

            {{-- Kolom Kiri: Form Create Profile --}}
            <div class="min-w-0 h-full p-8 flex flex-col justify-center overflow-y-auto">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Create your profile</h1>

                <form method="POST" action="{{ route('register.postProfile') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    {{-- Upload Photo Profile --}}
                    <div class="flex items-center gap-4 mb-2">
                        <label for="avatar" class="cursor-pointer group relative">
                            <div class="w-20 h-20 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center overflow-hidden group-hover:bg-gray-300 transition-all">
                                <img id="avatar-preview" src="" alt="Preview" class="hidden w-full h-full object-cover">
                                <div id="avatar-icon" class="flex flex-col items-center justify-center text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-xs font-bold -mt-1">+</span>
                                </div>
                            </div>
                            <input id="avatar" type="file" name="avatar" accept="image/*" class="hidden" onchange="previewImage(event)">
                        </label>

                        <div class="text-xs text-gray-500">
                            <p class="font-medium text-gray-700">Upload Photo Profile</p>
                            <p>Choose File JPG/PNG, Max 2MB</p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('avatar')" class="mt-1" />

                    {{-- Full Name (Readonly) --}}
                    <div>
                        <label class="block text-sm font-semibold italic text-gray-800 mb-1">Full Name</label>
                        <input type="text" value="{{ session('register_step1.name') }}" disabled
                            class="w-full h-10 px-4 py-2 border border-gray-300 rounded-full text-sm bg-gray-200 text-gray-600 cursor-not-allowed">
                    </div>

                    {{-- Display Name --}}
                    <div>
                        <label for="display_name" class="block text-sm font-semibold italic text-gray-800 mb-1">Display Name</label>
                        <input id="display_name" type="text" name="display_name" value="{{ old('display_name', session('register_step2.display_name')) }}" required autofocus placeholder="Display Name"
                            class="w-full h-10 px-4 py-2 border border-gray-400 rounded-full text-sm bg-gray-100 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-950 focus:bg-white transition-all">
                        <x-input-error :messages="$errors->get('display_name')" class="mt-1" />
                    </div>

                    {{-- Email (Readonly) --}}
                    <div>
                        <label class="block text-sm font-semibold italic text-gray-800 mb-1">Email</label>
                        <input type="email" value="{{ session('register_step1.email') }}" disabled
                            class="w-full h-10 px-4 py-2 border border-gray-300 rounded-full text-sm bg-gray-200 text-gray-600 cursor-not-allowed">
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label for="bio" class="block text-sm font-semibold italic text-gray-800 mb-1">Bio</label>
                        <textarea id="bio" name="bio" rows="2"
                            class="w-full px-4 py-2 border border-gray-400 rounded-2xl text-sm bg-gray-100 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-950 focus:bg-white transition-all resize-none">{{ old('bio', session('register_step2.bio')) }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-1" />
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2 flex justify-center">
                        <button type="submit" class="w-32 border border-gray-400 bg-gray-200 rounded-full py-1.5 text-sm font-semibold italic text-gray-800 hover:bg-gray-300 transition-all">
                            {{ __('Next') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Kolom Kanan: Logo --}}
            <div class="min-w-0 h-full rounded-3xl bg-gray-300 p-6 flex items-center justify-center">
                <div class="w-full h-full bg-gray-200 rounded-4xl border border-gray-400 flex flex-col items-center justify-between py-6">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-2 bg-gray-100 rounded-full"></span>
                        <span class="w-10 h-2 bg-black rounded-full"></span>
                        <span class="w-6 h-2 bg-gray-100 rounded-full"></span>
                    </div>

                    <div class="flex-1 flex items-center justify-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="PlayAll">
                        </a>
                    </div>

                    <p class="text-2xl font-extrabold italic text-gray-900">PlayAll</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const icon = document.getElementById('avatar-icon');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    icon.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-guest-layout>