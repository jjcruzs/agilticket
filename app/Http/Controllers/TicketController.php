<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Respuesta;
use App\Models\Ticket;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $estadoFiltro = $request->input('estado_id');

        $tickets = Ticket::with(['estado', 'solicitante', 'responsable'])
            ->when($estadoFiltro, function ($query, $estadoFiltro) {
                return $query->where('estado_id', $estadoFiltro);
            })
            ->latest()
            ->get();

        $estados = Estado::all();

        return view('autenticacion.tickets', compact('tickets', 'estados', 'estadoFiltro'));
    }

    public function create()
    {
        $estados = Estado::all();
        $usuarios = Usuario::all();

        return view('autenticacion.nuevo_ticket', compact('estados', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
            'estado_id' => 'required|exists:estados,id',
            'responsable_id' => 'nullable|exists:usuarios,id',
        ]);

        Ticket::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
            'estado_id' => $request->estado_id,
            'solicitante_id' => Auth::user()->id,
            'responsable_id' => $request->responsable_id,
        ]);

        $rol = strtolower(Auth::user()->rol->nombre ?? '');
        if (in_array($rol, ['admin', 'administrador'])) {
            return redirect()->route('admin.dashboard')->with('success', 'Ticket creado exitosamente.');
        } elseif ($rol === 'soporte') {
            return redirect()->route('soporte.dashboard')->with('success', 'Ticket creado exitosamente.');
        } else {
            return redirect()->route('tickets.dashboard')->with('success', 'Ticket creado exitosamente.');
        }
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'estado',
            'solicitante',
            'responsable',
            'respuestas.usuario',
            'respuestas.estado',
        ])->findOrFail($id);

        $estados = Estado::all();

        return view('tickets.show', compact('ticket', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'estado_id' => 'nullable|exists:estados,id',
            'responsable_id' => 'nullable|exists:usuarios,id',
        ]);

        $ticket->update([
            'estado_id' => $request->estado_id ?? $ticket->estado_id,
            'responsable_id' => $request->responsable_id ?? $ticket->responsable_id,
        ]);

        return redirect()->route('admin.tickets')->with('success', 'Ticket actualizado correctamente.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.tickets')->with('success', 'Ticket eliminado correctamente.');
    }

    public function edit($id)
    {
        $ticket = Ticket::with(['solicitante', 'responsable'])->findOrFail($id);
        $usuarios = Usuario::all();

        return view('tickets.edit', compact('ticket', 'usuarios'));
    }

    public function responder(Request $request, $ticketId)
    {
        $request->validate([
            'respuesta' => 'required|string|max:2000',
            'estado_id' => 'required|exists:estados,id',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        $respuesta = new Respuesta;
        $respuesta->ticket_id = $ticket->id;
        $respuesta->usuario_id = Auth::user()->id;
        $respuesta->estado_id = $request->estado_id;
        $respuesta->contenido = $request->respuesta;
        $respuesta->save();

        $ticket->estado_id = $request->estado_id;
        $ticket->save();

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Respuesta agregada correctamente.');
    }
}
