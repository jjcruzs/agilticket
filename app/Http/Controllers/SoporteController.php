<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Estado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoporteController extends Controller
{    
    public function dashboard()
    {
        $pendientes = Ticket::where('estado_id', 1)->count();
        $enProceso = Ticket::where('estado_id', 2)->count();
        $resueltos = Ticket::where('estado_id', 3)->count();
        $total = Ticket::count();

        $ticketsRecientes = Ticket::with(['solicitante', 'estado', 'responsable'])
                                  ->latest()
                                  ->take(10)
                                  ->get();

        return view('soporte.dashboard_soporte', compact(
            'pendientes', 'enProceso', 'resueltos', 'total', 'ticketsRecientes'
        ));
    }
    
    public function index(Request $request)
    {
        $estadoFiltro = $request->input('estado_id');
        $responsableFiltro = $request->input('responsable_id');
        $radicadoFiltro = $request->input('radicado');

        $tickets = Ticket::with(['estado','solicitante','responsable'])
                         ->when($estadoFiltro, fn($q) => $q->where('estado_id', $estadoFiltro))
                         ->when($responsableFiltro, fn($q) => $q->where('responsable_id', $responsableFiltro))
                         ->when($radicadoFiltro, fn($q) => $q->where('radicado', 'like', "%$radicadoFiltro%"))
                         ->latest()
                         ->get();

        $estados = Estado::all();
        $responsables = Usuario::where('rol_id', 3)->get();

        return view('soporte.tickets_soporte', compact(
            'tickets', 'estados', 'responsables', 'estadoFiltro', 'responsableFiltro', 'radicadoFiltro'
        ));
    }
    
    public function show(Ticket $ticket)
    {
        $estados = Estado::all();
        return view('soporte.show_soporte', compact('ticket','estados'));
    }
    
    public function responder(Request $request, Ticket $ticket)
    {
        $request->validate([
            'respuesta' => 'required|string',
            'estado_id' => 'required|exists:estados,id',
        ]);

        $ticket->respuestas()->create([
            'usuario_id' => Auth::user()->id,
            'contenido' => $request->respuesta,
            'estado_id' => $request->estado_id,
        ]);

        $ticket->estado_id = $request->estado_id;
        $ticket->save();

        return redirect()->route('soporte.tickets.show', $ticket->id)
                         ->with('success', 'Respuesta enviada correctamente.');
    }
}
