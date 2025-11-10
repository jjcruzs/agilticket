<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioDashboardController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login');
        } 
        
        // Filtros
        $radicado = $request->input('radicado');
        $titulo = $request->input('titulo');
        $estadoFiltro = $request->input('estado_id');
        $fecha = $request->input('fecha'); 
        
        // Contadores
        $pendientes = Ticket::where('solicitante_id', $usuario->id)->where('estado_id', 1)->count();
        $enProceso = Ticket::where('solicitante_id', $usuario->id)->where('estado_id', 2)->count();
        $resueltos = Ticket::where('solicitante_id', $usuario->id)->where('estado_id', 3)->count();
        $total = Ticket::where('solicitante_id', $usuario->id)->count();
         
        // Tickets recientes
        $ticketsRecientes = Ticket::where('solicitante_id', $usuario->id)
            ->with('estado')
            ->latest('fecha_creacion')
            ->take(5)
            ->get();

        // Historial con filtros
        $historialTickets = Ticket::where('solicitante_id', $usuario->id)
            ->with('estado')
            ->when($radicado, function ($query, $radicado) {
                return $query->where('radicado', 'like', '%' . $radicado . '%');
            })
            ->when($titulo, function ($query, $titulo) {
                return $query->where('titulo', 'like', '%' . $titulo . '%');
            })
            ->when($estadoFiltro, function ($query, $estadoFiltro) {
                return $query->where('estado_id', $estadoFiltro);
            })
            ->when($fecha, function ($query, $fecha) {
                return $query->whereDate('fecha_creacion', $fecha);
            })
            ->orderBy('fecha_creacion', 'desc')
            ->get();
 
        $estados = Estado::all();

        return view('usuario.dashboard_usuario', compact(
            'pendientes',
            'enProceso',
            'resueltos',
            'total',
            'ticketsRecientes',
            'historialTickets',
            'estados',
            'titulo',
            'estadoFiltro',
            'fecha',
            'radicado'
        ));
    }
}
