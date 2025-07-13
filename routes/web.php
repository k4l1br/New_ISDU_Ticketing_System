<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\reqOfficeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\ticket;

// Redirect root to login
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
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tickets', [ticketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [ticketController::class, 'create'])->name('pages.ticket.create');
    Route::resource('ticket', ticketController::class);

    // Requesting Office routes
    Route::get('/reqOffice/create', [reqOfficeController::class, 'create'])->name('reqOffice.create');
    Route::resource('/reqOffice', reqOfficeController::class);

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-data', [DashboardController::class, 'getData']);
    Route::get('/dashboard-per-unit', [DashboardController::class, 'getTicketsPerUnit']);
    Route::get('/dashboard-tasks-report', [DashboardController::class, 'tasksReport']);

    // Position page route 
    Route::get('/position', [PositionController::class, 'index'])->name('position.index');
    Route::middleware(['auth'])->group(function () {
    // Other routes...

    Route::resource('position', PositionController::class);
});
});
