<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * 🏠 Dashboard principal del administrador
     */
    public function dashboard()
    {
        // Conteos de estados
        $pendientes = Ticket::where('estado_id', 1)->count(); // Pendientes
        $proceso = Ticket::where('estado_id', 2)->count();    // En proceso
        $resueltos = Ticket::where('estado_id', 3)->count();  // Resueltos
        $total = Ticket::count();

        // Tickets recientes (por fecha de creación)
        $recientes = Ticket::orderBy('fecha_creacion', 'desc')->take(5)->get();

        return view('autenticacion.admin', compact('pendientes', 'proceso', 'resueltos', 'total', 'recientes'));
    }

    /**
     * 🎫 Listado de tickets
     */
    public function tickets()
    {
        $tickets = Ticket::with(['estado', 'solicitante', 'responsable'])->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * 🆕 Formulario para crear un nuevo ticket
     */
    public function nuevoTicket()
    {
        $usuarios = User::all();
        return view('autenticacion.nuevo_ticket', compact('usuarios'));
    }

    /**
     * 👥 Listar todos los usuarios
     */
    public function usuarios()
    {
        $usuarios = User::with('rol')->get();
        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * ➕ Formulario para crear usuario
     */
    public function crearUsuario()
    {
        $roles = Rol::all();
        return view('admin.crear_usuario', compact('roles'));
    }

    /**
     * 💾 Guardar usuario nuevo
     */
    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
            'rol_id' => 'required|integer'
        ]);

        User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('admin.usuarios')->with('success', '✅ Usuario creado correctamente.');
    }

    /**
     * ✏️ Editar usuario
     */
    public function editarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::all();
        return view('admin.editar_usuario', compact('usuario', 'roles'));
    }

    /**
     * 🔄 Actualizar usuario
     */
    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
            'rol_id' => 'required|integer'
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('admin.usuarios')->with('success', '✅ Usuario actualizado correctamente.');
    }

    /**
     * 🗑️ Eliminar usuario
     */
    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', '🗑️ Usuario eliminado correctamente.');
    }
}
