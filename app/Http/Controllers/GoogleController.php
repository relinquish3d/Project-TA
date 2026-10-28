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
            $googleUser = Socialite::driver('google')->stateless()->user();
            $findUser = User::where('id_google', $googleUser->id)
            ->orWhere('email', $googleUser->email)->first();

            if ($findUser) {
                if (!$findUser->id_google) {
                    $findUser->update(['id_google' => $googleUser->id]);
                }

                Auth::login($findUser);
                request()->session()->regenerate();

                if ($findUser->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                return redirect()->route('dashboard');
            }

            session()->put('register_step1',[
                'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                'email' => $googleUser->getEmail(),
                'id_google' => $googleUser->getId(),
                'is_google' => true,
            ]);
            return redirect()->route('register.profile-create');
                
        } catch (Throwable $e) {
            return redirect()->route('login')
            ->withErrors(['error' => 'Terjadi Kesalahan Saat Login' . $e->getMessage()]);
        }
    }
}
