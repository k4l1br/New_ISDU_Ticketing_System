<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ticketController;
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
    Route::get('/tickets', [ticketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [App\Http\Controllers\TicketController::class, 'create'])->name('pages.ticket.create');
    Route::resource('ticket', \App\Http\Controllers\TicketController::class);
    
});