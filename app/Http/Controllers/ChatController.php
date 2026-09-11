<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
public function index()
{
    $authId = auth()->id();
    $users = User::where('id_user', '!=', $authId)->get();
    $activeUser = null;
    $messages = collect();

    // DIUBAH: 'chat' menjadi 'chat.chat'
    return view('auth.chat.chat', compact('users', 'activeUser', 'messages'));
}

public function show($id = null)
{
    $authId = auth()->id();
    $users = User::where('id_user', '!=', $authId)->get();

    $activeUser = null;
    $messages = collect();

    if ($id) {
        $activeUser = User::find($id);

        if ($activeUser) {
            $messages = Message::where(function ($query) use ($authId, $id) {
                $query->where('sender_id', $authId)->where('receiver_id', $id);
            })->orWhere(function ($query) use ($authId, $id) {
                $query->where('sender_id', $id)->where('receiver_id', $authId);
            })->orderBy('created_at', 'asc')->get();
        }
    }

    // DIUBAH: 'chat' menjadi 'chat.chat'
    return view('chat.chat', compact('users', 'activeUser', 'messages'));
}
}