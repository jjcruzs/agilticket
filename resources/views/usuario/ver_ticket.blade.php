@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="bi bi-ticket-detailed me-2"></i> Detalle del Ticket #{{ $ticket->id }}
        </div>
        <div class="card-body">
            <p><strong>Título:</strong> {{ $ticket->titulo }}</p>
            <p><strong>Descripción:</strong> {{ $ticket->descripcion }}</p>
            <p><strong>Estado:</strong> 
                <span class="badge 
                    @if($ticket->estado == 'Pendiente') bg-warning text-dark 
                    @elseif($ticket->estado == 'En Proceso') bg-info text-dark 
                    @elseif($ticket->estado == 'Resuelto') bg-success 
                    @else bg-secondary @endif">
                    {{ $ticket->estado }}
                </span>
            </p>
            <p><strong>Creado:</strong> {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
            <a href="{{ route('usuario.dashboard_usuario') }}" class="btn btn-outline-secondary mt-3">
                <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
