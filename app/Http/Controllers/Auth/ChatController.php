<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class ChatController extends Controller
{
    public function index()
    {
        $authId = Auth::id();
        $users = User::where('id', '!=', $authId)->get();
        $activeUser = null;
        $messages = collect();

        return view('auth.chat.chat', compact('users', 'activeUser', 'messages'));
    }

    public function show($id = null)
    {
        $authId = Auth::id();
        $users = User::where('id', '!=', $authId)->get();

        $activeUser = null;
        $messages = collect();

        if ($id) {
            $activeUser = User::where('id', $id)->first();

            if ($activeUser) {
                $messages = Message::where(function ($query) use ($authId, $id) {
                    $query->where('sender_id', $authId)->where('receiver_id', $id);
                })->orWhere(function ($query) use ($authId, $id) {
                    $query->where('sender_id', $id)->where('receiver_id', $authId);
                })->orderBy('created_at', 'asc')->get();
            }
        }

        return view('auth.chat.chat', compact('users', 'activeUser', 'messages'));
    }
}