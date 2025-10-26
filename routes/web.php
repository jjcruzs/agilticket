<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AutenticacionController::class, 'showLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'login'])->name('login.post');

Route::get('/register', [AutenticacionController::class, 'showRegister'])->name('register');
Route::post('/register', [AutenticacionController::class, 'register'])->name('register.post');

Route::post('/logout', [AutenticacionController::class, 'logout'])->name('logout');


// ✅ RUTA CENTRAL DEL DASHBOARD (soluciona tu error)
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Tomamos el nombre del rol asociado al usuario
    $rol = $user->rol->nombre ?? null;

    if ($rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($rol === 'soporte') {
        return redirect()->route('soporte.dashboard');
    }

    // Si no es admin ni soporte, va al dashboard de tickets
    return redirect()->route('tickets.dashboard');
})->middleware(['auth'])->name('dashboard');


// ✅ Panel del administrador con datos reales
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Usuarios
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/usuarios/crear', [AdminController::class, 'crearUsuario'])->name('admin.usuarios.crear');
    Route::post('/admin/usuarios', [AdminController::class, 'guardarUsuario'])->name('admin.usuarios.guardar');
    Route::get('/admin/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])->name('admin.usuarios.editar');
    Route::put('/admin/usuarios/{id}', [AdminController::class, 'actualizarUsuario'])->name('admin.usuarios.actualizar');
    Route::delete('/admin/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.eliminar');

    // ✅ Tickets
    Route::get('/admin/tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
    Route::get('/admin/tickets/nuevo', [AdminController::class, 'nuevoTicket'])->name('admin.tickets.nuevo');
});

// ✅ Panel de soporte
Route::get('/soporte/dashboard', function () {
    return view('autenticacion.soporte');
})->middleware('auth')->name('soporte.dashboard');

// ✅ Panel de usuario normal
Route::get('/tickets/dashboard', function () {
    return view('tickets.dashboard');
})->middleware('auth')->name('tickets.dashboard');

// ✅ Rutas de tickets
Route::get('/tickets/nuevo', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');


