<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;



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

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id?}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id}', [ChatController::class, 'send'])->name('chat.send');
});
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
    Route::get('register/step-1', function() { return redirect()->route('register'); });
    Route::post('register/step-1', [RegisteredUserController::class, 'postStep1'])->name('register.step1');

    // Step 2: Create Profile
    Route::get('create-profile', [RegisteredUserController::class, 'showCreateProfile'])->name('register.profile');
    Route::post('create-profile', [RegisteredUserController::class, 'postStep2'])->name('register.postProfile');

    // Step 3: Choose Game
    Route::get('choose-game', [RegisteredUserController::class, 'showChooseGame'])->name('register.game');
    Route::post('register/complete', [RegisteredUserController::class, 'store'])->name('register.complete');
});