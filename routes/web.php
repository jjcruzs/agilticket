<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;
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

    $rol = strtolower($user->rol->nombre ?? '');

    if (in_array($rol, ['admin', 'administrador'])) {
        return redirect()->route('admin.dashboard');
    } elseif ($rol === 'soporte') {
        return redirect()->route('soporte.dashboard');
    } else {
        return redirect()->route('tickets.dashboard');
    }
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
    Route::post('/admin/tickets', [TicketController::class, 'store'])->name('admin.tickets.store');
    Route::get('/admin/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/admin/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/admin/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/admin/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    Route::post('/admin/tickets/{id}/responder', [TicketController::class, 'responder'])->name('tickets.responder');

    Route::get('/admin/reportes', [AdminController::class, 'reportForm'])->name('admin.reportes');
    Route::post('/admin/reportes/generar', [AdminController::class, 'generateReport'])->name('admin.reportes.generar');
});

// PANEL DE SOPORTE

Route::middleware(['auth'])->group(function () {
    Route::get('/soporte/dashboard', function () {
        return view('autenticacion.soporte');
    })->name('soporte.dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/tickets/dashboard', function () {
        $usuario = Auth::user();

        if (! $usuario) {
            return redirect()->route('login');
        }

        $pendientes = Ticket::where('solicitante_id', $usuario->id)->where('estado_id', 1)->count();
        $enProceso = Ticket::where('solicitante_id', $usuario->id)->where('estado_id', 2)->count();
        $resueltos = Ticket::where('solicitante_id', $usuario->id)->where('estado_id', 3)->count();
        $total = Ticket::where('solicitante_id', $usuario->id)->count();

        $ticketsRecientes = Ticket::where('solicitante_id', $usuario->id)
            ->with('estado')
            ->latest()
            ->take(5)
            ->get();

        return view('tickets.dashboard', compact(
            'pendientes',
            'enProceso',
            'resueltos',
            'total',
            'ticketsRecientes'
        ));
    })->name('tickets.dashboard');

    Route::get('/tickets/nuevo', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
});
