<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AutenticacionController::class, 'showLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'login'])->name('login.post');

Route::get('/register', [AutenticacionController::class, 'showRegister'])->name('register');
Route::post('/register', [AutenticacionController::class, 'register'])->name('register.post');

Route::post('/logout', [AutenticacionController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
