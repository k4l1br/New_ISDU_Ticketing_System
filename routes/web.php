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

});
