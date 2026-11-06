<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // Menampilkan halaman daftar teman & request add
   public function index()
{
    $userId = Auth::id();

    // 1. Permintaan pertemanan yang masuk
    $incomingRequests = Invite::with('sender')
        ->where('receiver_id', $userId)
        ->where('status', 'pending')
        ->get();

    // 2. Daftar teman yang sudah diterima
    $friends = Invite::with(['sender', 'receiver'])
        ->where('status', 'accepted')
        ->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        })
        ->get()
        ->map(function ($friendship) use ($userId) {
            return $friendship->sender_id == $userId
                ? $friendship->receiver
                : $friendship->sender;
        });

    // 3. ID user yang sudah berteman dengan user login
    $acceptedUserIds = Invite::where('status', 'accepted')
        ->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        })
        ->get()
        ->map(function ($invite) use ($userId) {
            return $invite->sender_id == $userId
                ? $invite->receiver_id
                : $invite->sender_id;
        });

    // 4. ID user yang mengirim permintaan kepada user login
    $incomingUserIds = Invite::where('receiver_id', $userId)
        ->where('status', 'pending')
        ->pluck('sender_id');

    // 5. ID user yang sudah dikirimi permintaan oleh user login
    $pendingUserIds = Invite::where('sender_id', $userId)
        ->where('status', 'pending')
        ->pluck('receiver_id')
        ->toArray();

    // 6. User yang tidak perlu ditampilkan di rekomendasi
    // Permintaan keluar Pending TIDAK dikecualikan.
    $excludedUserIds = $acceptedUserIds
        ->merge($incomingUserIds)
        ->push($userId)
        ->unique();

    // 7. Rekomendasi teman
    $usersToRecommend = User::whereNotIn('id', $excludedUserIds)
        ->where('role', '!=', 'admin')
        ->get();

    return view('friends.index', compact(
        'incomingRequests',
        'friends',
        'usersToRecommend',
        'pendingUserIds'
    ));
}

public function addFriend($receiver_id)
{

    if (Auth::user()->role === 'admin') {
        return back()->with('error', 'Admin tidak dapat menambahkan teman.');
    }

    $sender_id = Auth::id();

    if (!$receiver || $receiver->role === 'admin') {
        return back()->with('error', 'Pengguna tidak tersedia.');
    }

    // Tidak boleh menambahkan diri sendiri
    if ($sender_id == $receiver_id) {
        return back()->with('error', 'Kamu tidak bisa menambahkan diri sendiri.');
    }

    // Pastikan user tujuan tersedia
    if (!User::where('id', $receiver_id)->exists()) {
        return back()->with('error', 'Pengguna tidak ditemukan.');
    }

 $existingInvite = Invite::whereIn('status', ['pending', 'accepted'])
    ->where(function ($q) use ($sender_id, $receiver_id) {
        $q->where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)
                  ->where('receiver_id', $receiver_id);
        })
        ->orWhere(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)
                  ->where('receiver_id', $sender_id);
        });
    })
    ->first();

    if ($existingInvite) {
        return back()->with('error', 'Permintaan pertemanan sudah tersedia.');
    }

    // Simpan permintaan baru
    Invite::create([
        'sender_id' => $sender_id,
        'receiver_id' => $receiver_id,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Permintaan pertemanan berhasil dikirim!');
}

    // Terima Permintaan Pertemanan
    public function acceptFriend($id)
    {
    $invite = Invite::where('id_invite', $id)
        ->where('receiver_id', Auth::id())
        ->where('status', 'pending')
        ->firstOrFail();

    $invite->update([
        'status' => 'accepted'
    ]);

    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Permintaan pertemanan diterima!'
        ]);
    }

    return back()->with('success', 'Permintaan pertemanan diterima!');
}

public function removeFriend($id)
{
    $invite = Invite::where('id_invite', $id)
        ->where('receiver_id', Auth::id())
        ->where('status', 'pending')
        ->firstOrFail();

    $invite->delete();

    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Permintaan pertemanan berhasil ditolak.'
        ]);
    }

    return back()->with('success', 'Permintaan pertemanan berhasil ditolak.');
}
}