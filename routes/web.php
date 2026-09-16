<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryGameController;
use App\Http\Controllers\FriendController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

Route::get('/search-users', [UserController::class, 'search'])->name('users.search');

Route::get('/', function () {
    return view('landingPage');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware('web')->group(function () {
    Route::get('auth/google', [App\Http\Controllers\GoogleController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [App\Http\Controllers\GoogleController::class, 'handleGoogleCallback']);
});



Route::middleware('guest')->group(function () {
    // Tampilan Step 1
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

    // Redirect jika akses register/step-1 via GET (Mencegah error 405)
    Route::get('register/step-1', function () {
        return redirect()->route('register');
    });

    // Proses Submit Step 1 (POST)
    Route::post('register/step-1', [RegisteredUserController::class, 'postStep1'])->name('register.step1');

    // Tampilan Step 2 (Create Profile)
    Route::get('register/profile', [RegisteredUserController::class, 'showCreateProfile'])->name('register.profile');

    // Proses Submit Final Step 2 (POST)
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('guest')->group(function () {
    // Step 1: Register Account
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('register/step-1', function () {
        return redirect()->route('register');
    });
    Route::post('register/step-1', [RegisteredUserController::class, 'postStep1'])->name('register.step1');

    // Step 2: Create Profile
    Route::get('create-profile', [RegisteredUserController::class, 'showCreateProfile'])->name('register.profile');
    Route::post('create-profile', [RegisteredUserController::class, 'postStep2'])->name('register.postProfile');

    // Step 3: Choose Game
    Route::get('choose-game', [RegisteredUserController::class, 'showChooseGame'])->name('register.game');
    Route::post('register/complete', [RegisteredUserController::class, 'store'])->name('register.complete');
});

Route::middleware(['auth'])->group(function () {

    // === ROUTE FRIEND / ADD TEMAN ===
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends/add/{receiver_id}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::patch('/friends/accept/{id}', [FriendController::class, 'acceptFriend'])->name('friends.accept');
    Route::delete('/friends/remove/{id}', [FriendController::class, 'removeFriend'])->name('friends.remove');

    // === ROUTE ADMIN (CRUD ADMIN & KATEGORI GAME) ===
    Route::prefix('admin')->group(function () {
        // CRUD Admin Users
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

        // CRUD Kategori Game
        Route::get('/categories', [CategoryGameController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories', [CategoryGameController::class, 'store'])->name('admin.categories.store');
        Route::put('/categories/{id}', [CategoryGameController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [CategoryGameController::class, 'destroy'])->name('admin.categories.destroy');
    });
});


Route::get('/api/search-users', function (Request $request) {
    $query = $request->get('q');
    if (!$query) return response()->json([]);

    $userId = Auth::id(); // Ambil ID user yang lagi login

    $users = User::where('name', 'LIKE', '%' . $query . '%')
        ->when($userId, function ($q) use ($userId) {
            return $q->where('id', '!=', $userId);
        })
        ->limit(5)
        ->get(['id', 'name']);

    return response()->json($users);
})->name('users.search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
