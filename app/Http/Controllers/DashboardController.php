<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Invite;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // 1. Teman Mabar Aktif
        $activeFriendsCount = Invite::where('status', 'accepted')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            })->count();

        // 2. Undangan Baru
        $pendingInvites = Invite::with('sender')
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingInvitesCount = $pendingInvites->count();

        // 3. Sesi bermain
        $gamingSessionsThisWeek = 18;

        // 4. Chat Terbaru
        $recentChats = User::where('id', '!=', $userId)->get()->map(function ($u) use ($userId) {
            $u->last_message = $u->lastMessageWith($userId);
            return $u;
        })->filter(fn($u) => $u->last_message !== null)
          ->sortByDesc(fn($u) => $u->last_message?->created_at)
          ->take(6)
          ->values();

        return view('dashboard', compact(
            'user',
            'activeFriendsCount',
            'pendingInvites',
            'pendingInvitesCount',
            'gamingSessionsThisWeek',
            'recentChats'
        ));
    }
}
