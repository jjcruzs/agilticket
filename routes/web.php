<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AutenticacionController::class, 'showLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'login'])->name('login.post');

Route::get('/register', [AutenticacionController::class, 'showRegister'])->name('register');
Route::post('/register', [AutenticacionController::class, 'register'])->name('register.post');

Route::post('/logout', [AutenticacionController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if (! $user) {
        return redirect()->route('login');
    }

    $rol = $user->rol->nombre ?? null;

    if ($rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($rol === 'soporte') {
        return redirect()->route('soporte.dashboard');
    }

    return redirect()->route('tickets.dashboard');
})->middleware(['auth'])->name('dashboard');

// PANEL DEL ADMINISTRADOR
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/usuarios/crear', [AdminController::class, 'crearUsuario'])->name('admin.usuarios.crear');
    Route::post('/admin/usuarios', [AdminController::class, 'guardarUsuario'])->name('admin.usuarios.guardar');
    Route::get('/admin/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])->name('admin.usuarios.editar');
    Route::put('/admin/usuarios/{id}', [AdminController::class, 'actualizarUsuario'])->name('admin.usuarios.actualizar');
    Route::delete('/admin/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.eliminar');

    Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets');

    Route::get('/admin/tickets/nuevo', [TicketController::class, 'create'])->name('admin.tickets.nuevo');

    Route::get('/admin/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::put('/admin/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/admin/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
});

Route::get('/soporte/dashboard', function () {
    return view('autenticacion.soporte');
})->middleware('auth')->name('soporte.dashboard');

Route::get('/tickets/dashboard', function () {
    return view('tickets.dashboard');
})->middleware('auth')->name('tickets.dashboard');

Route::get('/tickets/nuevo', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
