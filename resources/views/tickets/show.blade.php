@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Detalle del Ticket #{{ $ticket->id }}</h3>
    <div class="card p-3">
        <p><strong>Título:</strong> {{ $ticket->titulo }}</p>
        <p><strong>Descripción:</strong> {{ $ticket->descripcion }}</p>
        <p><strong>Prioridad:</strong> {{ $ticket->prioridad }}</p>
        <p><strong>Estado:</strong> {{ $ticket->estado->nombre }}</p>
        <p><strong>Solicitante:</strong> {{ $ticket->solicitante->nombre }}</p>
        <p><strong>Responsable:</strong> {{ $ticket->responsable->nombre ?? 'Sin asignar' }}</p>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary mt-2">Volver</a>
    </div>
</div>
@endsection
