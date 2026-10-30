<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CategoryGame;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Step 1: Form Register
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses Step 1 (Simpan Data Account ke Session)
     */
    public function postStep1(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->session()->put('register_step1', $validated);
        $request->session()->save();

        return redirect()->route('register.profile');
    }

    /**
     * Step 2: Form Create Profile
     */
    public function showCreateProfile(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('register_step1')) {
            return redirect()->route('register');
        }

        return view('auth.create-profile');
    }

    /**
     * Proses Step 2 (Simpan Profile ke Session)
     */
    public function postStep2(Request $request): RedirectResponse
    {
        if (!$request->session()->has('register_step1')) {
            return redirect()->route('register');
        }

        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:500'],
            'avatar'       => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Handling temporary avatar upload jika ada
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars_temp', 'public');
            $validated['avatar'] = $avatarPath;
        } else {
            $validated['avatar'] = session('register_step2.avatar', null);
        }

        // Menyimpan data profile ke session
        $request->session()->put('register_step2', $validated);
        
        // Memaksa Laravel menyimpan session ke storage saat ini juga agar tidak perlu klik 2 kali
        $request->session()->save();

        return redirect()->route('register.game');
    }

    /**
     * Step 3: Form Choose Game
     */
    public function showChooseGame(Request $request): View|RedirectResponse
    {
        // Menggunakan properti dari objek $request secara langsung untuk akurasi data
        if (!$request->session()->has('register_step1') || !$request->session()->has('register_step2')) {
            return redirect()->route('register');
        }

        $categories = CategoryGame::all();
        return view('auth.choose-game', compact('categories'));
    }

    /**
     * Proses Step 3 (Final Store ke Database)
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->session()->has('register_step1') || !$request->session()->has('register_step2')) {
            return redirect()->route('register');
        }

        $step1 = $request->session()->get('register_step1');
        $step2 = $request->session()->get('register_step2');

        $request->validate([
            'games' => ['required', 'array', 'min:1'],
            'games.*' => ['string'],
        ], [
            'games.required' => 'Silahkan pilih minimal 1 game!',
            'games.*.required' => 'Silahkan pilih minimal 1 game!',
        ]);

        // Password hash google / manual
        $password = !empty($step1['password'])
         ? Hash::make($step1['password'])
         : Hash::make(Str::random(20));

        // Simpan User Baru
        $user = User::create([
            'name'           => $step1['name'],
            'email'          => $step1['email'],
            'id_google'      => $step1['id_google'] ?? null,
            'email_verified_at' => !empty($step1['id_google']) ? now() : null,
            'password'       => $password,
            'display_name'   => $step2['display_name'],
            'bio'            => $step2['bio'] ?? null,
            'avatar'         => $step2['avatar'] ?? null,
            'favorite_games' => $request->games, 
        ]);

        event(new Registered($user));

        // Hapus session registrasi
        $request->session()->forget(['register_step1', 'register_step2']);
        $request->session()->save();

        // Login Otomatis
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
