<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public static function getChatData($activeUserId = null)
    {
        $authId = Auth::id();

        // Ambil semua pengguna selain diri sendiri dan bukan admin
        $users = User::where('id', '!=', $authId)
            ->where(function ($q) {
                $q->where('role', '!=', 'admin')
                  ->orWhereNull('role');
            })
            ->where(function ($q) {
                $q->whereRaw('LOWER(name) != ?', ['admin'])
                  ->whereRaw('LOWER(email) not like ?', ['%admin%']);
            })
            ->get()
            ->map(function ($u) use ($authId) {
                $u->last_message = $u->lastMessageWith($authId);
                return $u;
            })
            ->sortByDesc(fn($u) => $u->last_message?->created_at)
            ->values();

        $activeUser = null;
        if ($activeUserId) {
            $candidate = User::find($activeUserId);
            if ($candidate && ($candidate->role !== 'admin' && strtolower($candidate->name) !== 'admin')) {
                $activeUser = $candidate;
            }
        }
        if (!$activeUser && $users->isNotEmpty()) {
            $activeUser = $users->first();
        }

        $messages = collect();
        if ($activeUser) {
            $id = $activeUser->id;
            $messages = Message::where(function ($q) use ($authId, $id) {
                $q->where('sender_id', $authId)->where('receiver_id', $id);
            })->orWhere(function ($q) use ($authId, $id) {
                $q->where('sender_id', $id)->where('receiver_id', $authId);
            })->orderBy('created_at', 'asc')->get();
        }

        return compact('users', 'activeUser', 'messages');
    }

    public function index()
    {
        $dashboardData = DashboardController::getDashboardData();
        $chatData = self::getChatData();
        $openChat = true;

        return view('dashboard', array_merge($dashboardData, $chatData, compact('openChat')));
    }

    public function show($id)
    {
        $dashboardData = DashboardController::getDashboardData();
        $chatData = self::getChatData($id);
        $openChat = true;

        return view('dashboard', array_merge($dashboardData, $chatData, compact('openChat')));
    }

    public function send(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $id,
            'message' => $request->message,
        ]);

        try {
            $this->pusher()?->trigger("chat.{$id}", 'new-message', [
                'message' => $message->message,
                'sender_id' => $message->sender_id,
            ]);
        } catch (\Throwable $e) {}

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
            ]);
        }

        return redirect()->route('chat.show', $id);
    }

    public function typing($id)
    {
        try {
            $this->pusher()?->trigger("chat.{$id}", 'typing', [
                'from_id' => Auth::id(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json(['status' => 'ok']);
    }

    private function pusher(): ?\Pusher\Pusher
    {
        if (!env('PUSHER_APP_KEY')) {
            return null;
        }

        return new \Pusher\Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER', 'ap1'), 'useTLS' => true]
        );
    }
}
