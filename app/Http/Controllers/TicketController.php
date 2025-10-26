<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Estado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['estado', 'solicitante', 'responsable'])->get();
        return view('tickets.index', compact('tickets'));
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
        'solicitante_id' => Auth::user()->id, // ✅ ahora funciona
        'responsable_id' => $request->responsable_id,
    ]);

    return redirect()
        ->route('admin.dashboard')
        ->with('success', '✅ Ticket creado exitosamente.');
}


    public function show($id)
    {
        $ticket = Ticket::with(['estado', 'solicitante', 'responsable'])->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }
}
