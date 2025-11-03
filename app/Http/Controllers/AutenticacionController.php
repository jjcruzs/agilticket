<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AutenticacionController extends Controller
{
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

            switch ($usuario->rol_id) {
                case 1: // Administrador
                    return redirect()->route('admin.dashboard');
                case 2: // Usuario normal
                    return redirect()->route('usuario.dashboard_usuario');
                case 3: // Soporte
                    return redirect()->route('soporte.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['error' => 'Rol no válido']);
            }
        }

        return back()->withErrors(['correo' => 'Correo o contraseña incorrectos']);
    }

    public function showRegister()
    {
        // ❌ Ya no se envían roles a la vista
        return view('autenticacion.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 🔹 Crear usuario con rol_id = 2 (Usuario por defecto)
        $usuario = User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => 2,
        ]);

        Auth::login($usuario);

        return redirect()->route('usuario.dashboard_usuario');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
