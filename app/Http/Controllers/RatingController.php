<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * RatingController
 * 
 * Mengelola fitur rating/ulasan antar pengguna.
 * - store():  Menyimpan rating baru (atau update jika sudah pernah merating user tersebut)
 * - Satu user hanya bisa memberi 1 rating ke user tertentu (updateOrCreate)
 */
class RatingController extends Controller
{
    /**
     * Simpan atau update rating untuk user target.
     *
     * Validasi:
     * - stars:   wajib, angka, min 0.5, max 5 (mendukung setengah bintang)
     * - comment: opsional, maksimal 500 karakter
     * 
     * Logika:
     * - Tidak bisa merating diri sendiri
     * - Jika sudah pernah rating user tersebut, rating akan diupdate
     * - Jika belum, rating baru akan dibuat
     */
    public function store(Request $request, User $user)
    {
        // Validasi input rating
        $request->validate([
            'stars'   => 'required|numeric|min:0.5|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Cegah user merating dirinya sendiri
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa merating diri sendiri.');
        }

        // Simpan atau update rating (1 reviewer hanya bisa 1 rating per target)
        Rating::updateOrCreate(
            [
                'reviewer_id' => Auth::id(),
                'target_id'   => $user->id,
            ],
            [
                'stars'   => $request->stars,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Rating berhasil dikirim!');
    }
}
