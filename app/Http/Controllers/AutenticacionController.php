<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AutenticacionController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        return view('autenticacion.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('correo', 'password');

    if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();

        $usuario = Auth::user();

        // 🔁 Redirigir según el rol numérico
        switch ($usuario->rol_id) {
            case 1: // Administrador
                return redirect()->route('admin.dashboard');
            case 2: // Soporte
                return redirect()->route('soporte.dashboard');
            case 3: // Usuario normal
            default:
                return redirect()->route('tickets.dashboard');
        }
    }

    return back()->withErrors(['correo' => 'Correo o contraseña incorrectos']);
}

    // Mostrar formulario de registro
    public function showRegister()
    {
        $roles = Rol::all(); // Trae todos los roles para el select
        return view('autenticacion.register', compact('roles'));
    }

    // Registrar usuario
    public function register(Request $request)
{
    // Validación
    $request->validate([
        'nombre' => 'required|string|max:255',
        'correo' => 'required|string|email|max:255|unique:usuarios,correo',
        'password' => 'required|string',
        'rol_id' => 'required|exists:roles,id',
    ]);

    // Crear usuario con contraseña hasheada
    $usuario = User::create([
        'nombre' => $request->nombre,
        'correo' => $request->correo,
        'password' => Hash::make($request->password),
        'rol_id' => $request->rol_id,
    ]);

    // Login automático
    Auth::login($usuario);

    // 🔁 Redirigir según el rol
    switch ($usuario->rol_id) {
        case 1: // Administrador
            return redirect()->route('admin.dashboard');
        case 2: // Soporte
            return redirect()->route('soporte.dashboard');
        case 3: // Usuario normal
        default:
            return redirect()->route('tickets.dashboard');
    }
}

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
