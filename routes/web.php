<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuarioDashboardController;
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

    if (!$user) {
        return redirect()->route('login');
    }

    switch ($user->rol_id) {
        case 1:
            return redirect()->route('admin.dashboard');
        case 2:
            return redirect()->route('usuario.dashboard_usuario');
        case 3:
            return redirect()->route('soporte.dashboard_soporte');
        default:
            return redirect()->route('login');
    }
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/usuarios/crear', [AdminController::class, 'crearUsuario'])->name('admin.usuarios.crear');
    Route::post('/admin/usuarios', [AdminController::class, 'guardarUsuario'])->name('admin.usuarios.guardar');
    Route::get('/admin/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])->name('admin.usuarios.editar');
    Route::put('/admin/usuarios/{id}', [AdminController::class, 'actualizarUsuario'])->name('admin.usuarios.actualizar');
    Route::delete('/admin/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.eliminar');

    Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets');
    Route::get('/admin/tickets/nuevo', [TicketController::class, 'createAdmin'])->name('admin.tickets.nuevo');
    Route::post('/admin/tickets', [TicketController::class, 'store'])->name('admin.tickets.store');
    Route::get('/admin/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/admin/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/admin/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/admin/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/admin/tickets/{id}/responder', [TicketController::class, 'responder'])->name('tickets.responder');

    Route::get('/admin/reportes', [AdminController::class, 'reportForm'])->name('admin.reportes');
    Route::post('/admin/reportes/generar', [AdminController::class, 'generateReport'])->name('admin.reportes.generar');
});


use App\Http\Controllers\SoporteController;

Route::prefix('soporte')->middleware('auth')->group(function () {

    
    Route::get('/dashboard', [SoporteController::class, 'dashboard'])
        ->name('soporte.dashboard_soporte');

    
    Route::get('/tickets', [SoporteController::class, 'index'])
        ->name('soporte.tickets');

    
    Route::get('/tickets/{ticket}', [SoporteController::class, 'show'])
        ->name('soporte.tickets.show');

    
    Route::post('/tickets/{ticket}/responder', [SoporteController::class, 'responder'])
        ->name('soporte.tickets.responder');

    
    Route::post('/tickets/{ticket}/resolver', [SoporteController::class, 'resolver'])
        ->name('soporte.tickets.resolver');
});



Route::middleware(['auth'])->group(function () {
    
    Route::get('/usuario/dashboard_usuario', [UsuarioDashboardController::class, 'index'])
        ->name('usuario.dashboard_usuario');
 
    
    Route::get('/tickets/nuevo', [TicketController::class, 'createUsuario'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
 
    
    Route::get('/tickets/{id}/ver', [TicketController::class, 'showUsuario'])->name('tickets.ver.usuario');
});
