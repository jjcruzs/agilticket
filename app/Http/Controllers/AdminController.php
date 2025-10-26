<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {

        $pendientes = Ticket::where('estado_id', 1)->count();
        $proceso = Ticket::where('estado_id', 2)->count();
        $resueltos = Ticket::where('estado_id', 3)->count();
        $total = Ticket::count();

        $recientes = Ticket::orderBy('fecha_creacion', 'desc')->take(5)->get();

        return view('autenticacion.admin', compact('pendientes', 'proceso', 'resueltos', 'total', 'recientes'));
    }

    public function tickets()
    {
        $tickets = Ticket::with(['estado', 'solicitante', 'responsable'])->get();

        return view('tickets.index', compact('tickets'));
    }

    public function nuevoTicket()
    {
        $usuarios = User::all();

        return view('autenticacion.nuevo_ticket', compact('usuarios'));
    }

    public function usuarios()
    {
        $usuarios = User::with('rol')->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    public function crearUsuario()
    {
        $roles = Rol::all();

        return view('admin.crear_usuario', compact('roles'));
    }

    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
            'rol_id' => 'required|integer',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
    }

    public function editarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::all();

        return view('admin.editar_usuario', compact('usuario', 'roles'));
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo,'.$usuario->id,
            'rol_id' => 'required|integer',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
}
