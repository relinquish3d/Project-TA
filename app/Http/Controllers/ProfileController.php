<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invite;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $reports = $user->reports()->latest()->get();
        $inProgressReportsCount = $user->reports()->where('status', 'in_progress')->count();
        $completedReportsCount = $user->reports()->where('status', 'completed')->count();

        // Ambil data dari kolom database favorite_games
        $rawGames = is_string($user->favorite_games) ? json_decode($user->favorite_games, true) : ($user->favorite_games ?? []);
        
        $favoriteGames = [];
        foreach ((array)$rawGames as $gameName) {
            // Pemetaan nama file di folder public/images/Game/
            $fileName = 'game.png'; // Default
            if (stripos($gameName, 'PUBG') !== false) {
                $fileName = 'PUBG.png';
            } elseif (stripos($gameName, 'ML') !== false || stripos($gameName, 'Mobile Legends') !== false) {
                $fileName = 'ML.png';
            } elseif (stripos($gameName, 'Valorant') !== false) {
                $fileName = 'Valorant.png';
            } elseif (stripos($gameName, 'FC') !== false) {
                $fileName = 'FC.png';
            }

            $favoriteGames[] = [
                'name' => $gameName,
                'image' => 'images/Game/' . $fileName,
                'rating' => '5.00'
            ];
        }

        return view('profile.edit', [
            'user' => $user,
            'reports' => $reports,
            'inProgressReportsCount' => $inProgressReportsCount,
            'completedReportsCount' => $completedReportsCount,
            'favoriteGames' => $favoriteGames,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Hanya mengambil input yang diizinkan untuk diubah
        $data = $request->only(['display_name', 'bio']);

        // Proses unggah foto avatar jika ada
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto ke folder storage/app/public/avatars
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Simpan perubahan ke database
        $user->update($data);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/dashboard');
    }

    /**
     * Display the specified user's profile.
     */
    public function show(User $user): View
    {
        $authUser = Auth::user();
        $reports = $authUser ? $authUser->reports()->latest()->get() : collect();
        $inProgressReportsCount = $authUser ? $authUser->reports()->where('status', 'in_progress')->count() : 0;
        $completedReportsCount = $authUser ? $authUser->reports()->where('status', 'completed')->count() : 0;

        // --- DATA RATING ---
        $ratings = $user->ratingsReceived()->with('reviewer')->latest()->get();
        $averageRating = $user->averageRating();
        $totalRatings = $ratings->count();
        $existingRating = $authUser
            ? \App\Models\Rating::where('reviewer_id', $authUser->id)->where('target_id', $user->id)->first()
            : null;

        // Ambil data game favorit dari database user profil yang sedang dilihat
        $rawGames = is_string($user->favorite_games) ? json_decode($user->favorite_games, true) : ($user->favorite_games ?? []);
        
        $favoriteGames = [];
        foreach ((array)$rawGames as $gameName) {
            $fileName = 'game.png'; // Default
            if (stripos($gameName, 'PUBG') !== false) {
                $fileName = 'PUBG.png';
            } elseif (stripos($gameName, 'ML') !== false || stripos($gameName, 'Mobile Legends') !== false) {
                $fileName = 'ML.png';
            } elseif (stripos($gameName, 'Valorant') !== false) {
                $fileName = 'Valorant.png';
            } elseif (stripos($gameName, 'FC') !== false) {
                $fileName = 'FC.png';
            }

            $favoriteGames[] = [
                'name' => $gameName,
                'image' => 'images/Game/' . $fileName,
                'rating' => '5.00'
            ];
        }

        return view('profile.show', compact(
            'user', 'reports', 'inProgressReportsCount', 'completedReportsCount',
            'ratings', 'averageRating', 'totalRatings', 'existingRating', 'favoriteGames'
        ));
    }
}