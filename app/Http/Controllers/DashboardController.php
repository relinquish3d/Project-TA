<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Invite;
use App\Models\User;

class DashboardController extends Controller
{
    public static function getDashboardData()
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }
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

        // 4. Chat Terbaru (Kecualikan akun admin)
        $recentChats = User::where('id', '!=', $userId)
            ->where(function ($q) {
                $q->where('role', '!=', 'admin')
                  ->orWhereNull('role');
            })
            ->where(function ($q) {
                $q->whereRaw('LOWER(name) != ?', ['admin'])
                  ->whereRaw('LOWER(email) not like ?', ['%admin%']);
            })
            ->get()->map(function ($u) use ($userId) {
                $u->last_message = $u->lastMessageWith($userId);
                return $u;
            })->filter(fn($u) => $u->last_message !== null)
              ->sortByDesc(fn($u) => $u->last_message?->created_at)
              ->take(6)
              ->values();

        return compact(
            'user',
            'activeFriendsCount',
            'pendingInvites',
            'pendingInvitesCount',
            'gamingSessionsThisWeek',
            'recentChats'
        );
    }

    public function index()
    {
        $dashboardData = self::getDashboardData();
        $chatData = ChatController::getChatData(request('chat_user'));
        $openChat = request()->boolean('chat') || request()->has('chat_user');

        return view('dashboard', array_merge($dashboardData, $chatData, compact('openChat')));
    }
}
