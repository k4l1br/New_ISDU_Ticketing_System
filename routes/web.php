<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('post', function () {
    return view('post');
});

// Authentication routes (only if available)
if (method_exists(Auth::class, 'routes')) {
    Auth::routes();
}

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});