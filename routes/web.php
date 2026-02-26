<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

use App\Http\Controllers\AuthController;

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);



use App\Http\Controllers\DashboardController;

Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index']);



use App\Http\Controllers\ColocationController;

Route::get('/colocation/create', [ColocationController::class, 'create']);
Route::post('/colocation/store', [ColocationController::class, 'store']);

