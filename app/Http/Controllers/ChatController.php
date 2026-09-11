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
        $users = User::where('user_id', '!=', $authId)->get();
        $activeUser = null;
        $messages = collect();

        return view('chat', compact('users', 'activeUser', 'messages'));
    }

    public function show($id = null)
    {
        $authId = auth()->id();
        $users = User::where('id', '!=', $authId)->get();

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

        return view('chat', compact('users', 'activeUser', 'messages'));
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $id,
            'message' => $request->message,
        ]);

        return redirect()->route('chat.show', $id);
    }
}