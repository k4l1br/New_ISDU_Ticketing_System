<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReqOfficeController;
use App\Http\Controllers\UserController; // Add this import

// Authentication Routes
Auth::routes();

// Redirect Root to Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
   Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tickets', [ticketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [ticketController::class, 'create'])->name('pages.ticket.create');
    Route::resource('ticket', ticketController::class);

    // Explicit route for Add Requesting Office form
    Route::get('/reqOffice/create', [reqOfficeController::class, 'create'])->name('reqOffice.create');

    // CRUD routes for Requesting Office (reqOffice)
    Route::resource('/reqOffice', reqOfficeController::class);


    // Profile
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    
    
    // Admin User Management
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });

   
});