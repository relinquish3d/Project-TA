<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import model User di sini

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('display_name', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'name', 'display_name', 'avatar']);

        return response()->json($users);
    }
}