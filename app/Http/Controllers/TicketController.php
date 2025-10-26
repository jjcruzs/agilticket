<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Estado;
use App\Models\Usuario;
use Illuminate\Http\Request;

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
        return view('tickets.create', compact('estados', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'prioridad' => 'required',
            'solicitante_id' => 'required',
        ]);

        Ticket::create($request->all());

        return redirect()->route('tickets.index')->with('success', 'Ticket creado exitosamente.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['estado', 'solicitante', 'responsable'])->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }
}
