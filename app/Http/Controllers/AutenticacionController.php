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

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('correo', 'password');

        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
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
            'password' => 'required|string', // ✅ Sin restricciones de longitud
            'rol_id' => 'required|exists:roles,id', // valida que el rol exista
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

        // Redirigir al dashboard
        return redirect()->route('dashboard');
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
