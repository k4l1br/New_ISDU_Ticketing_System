<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\reqOfficeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\ticket;

// Redirect Root to Login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('post', function () {
    return view('post');
});

// Authentication routes
if (method_exists(Auth::class, 'routes')) {
    Auth::routes();
}

// Protected routes
// Authenticated Routes
Route::middleware(['auth'])->group(function () {
   Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tickets', [ticketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [ticketController::class, 'create'])->name('pages.ticket.create');
    Route::resource('ticket', ticketController::class);

    // Requesting Office routes
    Route::get('/reqOffice/create', [reqOfficeController::class, 'create'])->name('reqOffice.create');
    Route::resource('/reqOffice', reqOfficeController::class);
});