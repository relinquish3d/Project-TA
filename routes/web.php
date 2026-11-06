<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CategoryGameController;
use App\Http\Controllers\FriendController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;


Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/search-users', [UserController::class, 'search'])->name('users.search');

Route::get('/', function () {
    return view('landingPage');
});

//dashboard user
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('patch.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id}', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/{id}/typing', [ChatController::class, 'typing'])->name('chat.typing');
});

Route::middleware('web')->group(function () {
    Route::get('auth/google', [App\Http\Controllers\GoogleController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [App\Http\Controllers\GoogleController::class, 'handleGoogleCallback']);
});

Route::middleware('guest')->group(function () {
    // Step 1: Register Account
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('register/step-1', function () {
        return redirect()->route('register');
    });
    Route::post('register/step-1', [RegisteredUserController::class, 'postStep1'])->name('register.step1');

    // Step 2: Create Profile (dukung register/profile dan create-profile)
    Route::get('register/profile', [RegisteredUserController::class, 'showCreateProfile'])->name('register.profile');
    Route::get('create-profile', [RegisteredUserController::class, 'showCreateProfile'])->name('register.profile-create');
    Route::post('create-profile', [RegisteredUserController::class, 'postStep2'])->name('register.postProfile');
    Route::post('register/profile', [RegisteredUserController::class, 'postStep2']);

    // Step 3: Choose Game
    Route::get('choose-game', [RegisteredUserController::class, 'showChooseGame'])->name('register.game');
    Route::post('register/complete', [RegisteredUserController::class, 'store'])->name('register.complete');
});

// Dashboard Admin
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard/admin', [
        \App\Http\Controllers\AdminController::class,
        'dashboard'
    ])->name('admin.dashboard');
});

Route::middleware(['auth'])->group(function () {

    // === ROUTE FRIEND / ADD TEMAN ===
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends/add/{receiver_id}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::patch('/friends/accept/{id}', [FriendController::class, 'acceptFriend'])->name('friends.accept');
    Route::delete('/friends/remove/{id}', [FriendController::class, 'removeFriend'])->name('friends.remove');

    // === ROUTE ADMIN (CRUD ADMIN & KATEGORI GAME) ===
    Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

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

// Fitur Report & Inbox Ticketing
Route::middleware(['auth'])->group(function () {
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::get('/inbox', [ReportController::class, 'inbox'])->name('inbox');
    Route::get('/reports/inbox', [ReportController::class, 'inbox'])->name('reports.inbox');
});

// Fitur Rating / Ulasan User
Route::middleware(['auth'])->group(function () {
    Route::post('/rating/{user}', [RatingController::class, 'store'])->name('rating.store');
});

require __DIR__ . '/auth.php';
