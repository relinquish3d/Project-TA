<?php
use Illuminate\Support\Facades\Route;

// Route untuk halaman chat
Route::get('/chat', function () {
    return view('chat');
})->name('chat.index');