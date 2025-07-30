<?php

use App\Http\Controllers\ticketController;
use App\Http\Controllers\reqOfficeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReferenceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\ticket;

// Redirect Root to Login
Route::get('/', function () {
    return redirect()->route('login');
});

// CSRF Token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('web');

// Home route to fix 'Route [home] not defined' error
Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('post', function () {
    return view('post');
});

// Authentication routes
if (method_exists(Auth::class, 'routes')) {
    Auth::routes();
}

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Routes accessible by both admin and super_admin
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/tickets', [ticketController::class, 'index'])->name('ticket.index');
        Route::get('/my-tickets', [ticketController::class, 'myTickets'])->name('tickets.my');
        
        // Ticket viewing (read-only for admin)
        Route::get('/ticket/{ticket}', [ticketController::class, 'show'])->name('ticket.show');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard-data', [DashboardController::class, 'getData']);
        Route::get('/dashboard-per-unit', [DashboardController::class, 'getTicketsPerUnit']);
        Route::get('/dashboard-tasks-report', [DashboardController::class, 'tasksReport']);
        Route::get('/home', fn() => redirect('/dashboard'))->name('home');
    });

    // Routes only accessible by super_admin
    Route::middleware(['role:super_admin'])->group(function () {
        // Ticket creation, editing, updating and deletion (super admin only)
        Route::get('/tickets/create', [ticketController::class, 'create'])->name('ticket.create');
        Route::post('/ticket', [ticketController::class, 'store'])->name('ticket.store');
        Route::get('/ticket/{ticket}/edit', [ticketController::class, 'edit'])->name('ticket.edit');
        Route::put('/ticket/{ticket}', [ticketController::class, 'update'])->name('ticket.update');
        Route::patch('/ticket/{ticket}', [ticketController::class, 'update']);
        Route::delete('/ticket/{ticket}', [ticketController::class, 'destroy'])->name('ticket.destroy');
        
        // Requesting Office
        Route::get('/reqOffice/create', [reqOfficeController::class, 'create'])->name('reqOffice.create');
        Route::resource('/reqOffice', reqOfficeController::class);

        // Position
        Route::get('/position', [PositionController::class, 'index'])->name('position.index');
        Route::resource('position', PositionController::class);

        // Admin User Management
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            Route::resource('offices', \App\Http\Controllers\Admin\OfficeController::class)->except(['show']);
        });

        //Reference
        Route::resource('references', ReferenceController::class);
        
        //Status
        Route::resource('status', \App\Http\Controllers\StatusController::class);
    });

    // Profile route accessible by all authenticated users
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
});