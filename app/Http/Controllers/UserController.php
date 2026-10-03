<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
public function search(Request $request)
{
    $query = $request->input('q');
    if (!$query) {
        return response()->json([]);
    }

    $currentUserId = Auth::id();

    $users = User::query()
        ->when($currentUserId, function ($q) use ($currentUserId) {
            $q->where('id', '!=', $currentUserId);
        })
        ->where(function ($q) use ($query) {
            $q->where('name', 'LIKE', '%' . $query . '%')
              ->orWhere('display_name', 'LIKE', '%' . $query . '%');
        })
        ->limit(5)
        ->get();

    return response()->json(
        $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->display_name ?? $user->name,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png'),
                'url' => route('profile.show', $user->id),
            ];
        })
    );
}
}