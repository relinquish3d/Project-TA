<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Gunakan stateless() jika sering kena InvalidStateException / Session mismatch
            $user = Socialite::driver('google')->stateless()->user();

            $finduser = User::where('id_google', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'id_google' => $user->id,
                    'email_verified_at' => now(), // Gunakan helper now() bawaan Laravel
                ]);

                Auth::login($newUser);
                return redirect()->intended('dashboard');    
            }

        } catch (Throwable $e) {
            // Dump objek exception utuh agar detail error/stack trace terlihat jelas
            dd($e); 
        }
    }
}