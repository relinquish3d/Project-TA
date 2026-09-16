<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', auth()->id())
                     ->where(function($q) use ($query) {
                         $q->where('name', 'LIKE', "%{$query}%")
                           ->orWhere('display_name', 'LIKE', "%{$query}%");
                     })
                     ->limit(6)
                     ->get();

        $result = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->display_name ?? $user->name,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png'),
                'url' => route('profile.edit', $user->id)
            ];
        });

        return response()->json($result);
    }
}