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

        // Permintaan Add Teman yang masuk (Pending)
        $incomingRequests = Invite::with('sender')
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->get();

        // Daftar Teman yang sudah di-accept
        $friends = Invite::with(['sender', 'receiver'])
            ->where('status', 'accepted')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            })
            ->get()
            ->map(function ($friendship) use ($userId) {
                return $friendship->sender_id == $userId ? $friendship->receiver : $friendship->sender;
            });

        // Daftar User yang belum di-add (rekomendasi teman)
        $usersToRecommend = User::where('id', '!=', $userId)
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('receiver_id')->from('invite')->where('sender_id', $userId)
                    ->union(
                        $query->select('sender_id')->from('invite')->where('receiver_id', $userId)
                    );
            })
            ->get();

        return view('friends.index', compact('incomingRequests', 'friends', 'usersToRecommend'));
    }

    // Kirim Permintaan Add Teman
    public function addFriend($receiver_id)
    {
        Invite::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver_id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permintaan pertemanan berhasil dikirim!');
    }

    // Terima Permintaan Pertemanan
    public function acceptFriend($id)
    {
        $request = Invite::where('id', $id)
            ->where('receiver_id', Auth::id())
            ->firstOrFail();

        $request->update(['status' => 'accepted']);

        return back()->with('success', 'Permintaan pertemanan diterima!');
    }

    // Tolak Permintaan / Hapus Teman (Unfriend)
    public function removeFriend($id)
    {
        $friendship = Invite::where(function ($q) use ($id) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $id);
        })->orWhere(function ($q) use ($id) {
            $q->where('sender_id', $id)->where('receiver_id', Auth::id());
        })->orWhere('id', $id)->first();

        if ($friendship) {
            $friendship->delete();
        }

        return back()->with('success', 'Data pertemanan berhasil diperbarui.');
    }
}
