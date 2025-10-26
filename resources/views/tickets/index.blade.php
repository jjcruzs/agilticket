@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Listado de Tickets</h2>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">+ Nuevo Ticket</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Solicitante</th>
                <th>Responsable</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->titulo }}</td>
                <td>{{ $ticket->prioridad }}</td>
                <td>{{ $ticket->estado->nombre }}</td>
                <td>{{ $ticket->solicitante->nombre }}</td>
                <td>{{ $ticket->responsable->nombre ?? 'Sin asignar' }}</td>
                <td>
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info btn-sm">Ver</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
