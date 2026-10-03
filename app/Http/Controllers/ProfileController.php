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

        return view('profile.edit', [
            'user' => $user,
            'reports' => $reports,
            'inProgressReportsCount' => $inProgressReportsCount,
            'completedReportsCount' => $completedReportsCount,
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
    public function show(User $user) {
        $authUser = Auth::user();
        $reports = $authUser ? $authUser->reports()->latest()->get() : collect();
        $inProgressReportsCount = $authUser ? $authUser->reports()->where('status', 'in_progress')->count() : 0;
        $completedReportsCount = $authUser ? $authUser->reports()->where('status', 'completed')->count() : 0;

        // --- DATA RATING ---
        // Ambil semua rating yang DITERIMA oleh user profil ini (beserta data reviewer)
        $ratings = $user->ratingsReceived()->with('reviewer')->latest()->get();
        // Hitung rata-rata rating (default 5.00 jika belum ada)
        $averageRating = $user->averageRating();
        // Jumlah total ulasan
        $totalRatings = $ratings->count();
        // Cek apakah user yang sedang login sudah pernah merating user ini
        $existingRating = $authUser
            ? \App\Models\Rating::where('reviewer_id', $authUser->id)->where('target_id', $user->id)->first()
            : null;

        return view('profile.show', compact(
            'user', 'reports', 'inProgressReportsCount', 'completedReportsCount',
            'ratings', 'averageRating', 'totalRatings', 'existingRating'
        ));
    }
}