<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\reqOfficeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
if (method_exists(Auth::class, 'routes')) {
    Auth::routes();
}

// Public route
Route::get('/post', function () {
    return view('post');
});

 Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Registration routes (optional)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});


// Protected routes
Route::middleware(['auth'])->group(function () {
    // 🏠 Home and Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home'); // Restore this for compatibility
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
 // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-data', [DashboardController::class, 'getData']);
    Route::get('/dashboard-per-unit', [DashboardController::class, 'getTicketsPerUnit']);
    Route::get('/dashboard-tasks-report', [DashboardController::class, 'tasksReport']);
    Route::get('/home', fn() => redirect('/dashboard'))->name('home');
    

    // 🎫 Tickets
    Route::prefix('tickets')->group(function () {
        Route::get('/', [ticketController::class, 'index'])->name('tickets');
        Route::get('/create', [ticketController::class, 'create'])->name('pages.ticket.create'); // FIXED route name
    });
    Route::resource('ticket', ticketController::class)->except(['create']);

    // Requesting Office
    Route::prefix('reqOffice')->group(function () {
        Route::get('/create', [reqOfficeController::class, 'create'])->name('reqOffice.create');
    });
    Route::resource('reqOffice', reqOfficeController::class)->except(['create']);

    // 🧑‍💼 Position
    Route::resource('position', PositionController::class);
    Route::get('/position', [PositionController::class, 'index'])->name('position.index');

    // 👤 User Profile
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');

    // 🔐 Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class);
    });

    // 📘 Reference
    Route::resource('references', ReferenceController::class);

    // 📌 Status
    Route::resource('status', StatusController::class);
});
