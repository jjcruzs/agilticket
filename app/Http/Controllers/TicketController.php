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
        $responsableFiltro = $request->input('responsable_id');
        $radicadoFiltro = $request->input('radicado');

        $tickets = Ticket::with(['estado', 'solicitante', 'responsable'])
            ->when($estadoFiltro, fn($q) => $q->where('estado_id', $estadoFiltro))
            ->when($responsableFiltro, fn($q) => $q->where('responsable_id', $responsableFiltro))
            ->when($radicadoFiltro, fn($q) => $q->where('radicado', 'like', "%$radicadoFiltro%"))
            ->latest()
            ->get();

        $estados = Estado::all();
        $responsables = Usuario::where('rol_id', 3)->get();

        return view('autenticacion.tickets', compact(
            'tickets', 'estados', 'responsables', 
            'estadoFiltro', 'responsableFiltro', 'radicadoFiltro'
        ));
    }

    public function create()
    {
        $estados = Estado::all();
        $usuarios = Usuario::where('rol_id', 3)->get();

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

        $ultimo = Ticket::latest('id')->first();
        $nuevoNumero = $ultimo ? $ultimo->id + 1 : 1;
        $radicado = 'TCK-' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);

        Ticket::create([
            'radicado' => $radicado,
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
            return redirect()->route('usuario.dashboard_usuario')->with('success', 'Ticket creado exitosamente.');
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

    public function edit($id)
    {
        $ticket = Ticket::with(['solicitante', 'responsable', 'estado'])->findOrFail($id);
        $estados = Estado::all();
        $usuarios = Usuario::where('rol_id', 3)->get(); // soporte

        return view('tickets.edit', compact('ticket', 'estados', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'estado_id' => 'nullable|exists:estados,id',
            'responsable_id' => 'nullable|exists:usuarios,id',
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'prioridad' => 'nullable|string',
        ]);

        $ticket->update([
            'titulo' => $request->titulo ?? $ticket->titulo,
            'descripcion' => $request->descripcion ?? $ticket->descripcion,
            'prioridad' => $request->prioridad ?? $ticket->prioridad,
            'estado_id' => $request->estado_id ?? $ticket->estado_id,
            'responsable_id' => $request->responsable_id ?? $ticket->responsable_id,
        ]);

        return redirect()->route('admin.tickets')->with('success', 'Ticket actualizado correctamente.');
    }

    public function responder(Request $request, $ticketId)
    {
        $request->validate([
            'respuesta' => 'required|string|max:2000',
            'estado_id' => 'required|exists:estados,id',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        $respuesta = new Respuesta();
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
