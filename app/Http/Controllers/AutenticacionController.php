<?php

namespace App\Http\Controllers;

use App\Models\Rol;
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
                case 2: // Soporte
                    return redirect()->route('soporte.dashboard');
                case 3: // Usuario normal
                default:
                    return redirect()->route('tickets.dashboard');
            }
        }

        return back()->withErrors(['correo' => 'Correo o contraseña incorrectos']);
    }

    public function showRegister()
    {
        $roles = Rol::all();

        return view('autenticacion.register', compact('roles'));
    }

    public function register(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios,correo',
            'password' => 'required|string',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $usuario = User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
        ]);

        Auth::login($usuario);

        // Redirigir según el rol
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
