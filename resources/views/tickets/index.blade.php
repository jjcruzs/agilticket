@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Historial de Tickets</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-purple">← Volver</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Solicitante</th>
                        <th>Responsable</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td class="fw-semibold text-dark">{{ $ticket->titulo }}</td>
                            <td>
                                @php
                                    $color = match($ticket->prioridad) {
                                        'Alta' => 'danger',
                                        'Media' => 'warning',
                                        'Baja' => 'success',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ $ticket->prioridad }}</span>
                            </td>
                            <td><span class="badge bg-info">{{ $ticket->estado->nombre ?? 'Sin estado' }}</span></td>
                            <td>{{ $ticket->solicitante->nombre ?? 'Desconocido' }}</td>
                            <td>{{ $ticket->responsable->nombre ?? 'Sin asignar' }}</td>
                            <td>
                                {{ $ticket->fecha_creacion 
                                    ? \Carbon\Carbon::parse($ticket->fecha_creacion)->format('d/m/Y H:i:s') 
                                    : 'Sin fecha' }}
                            </td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-outline-primary btn-sm">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay tickets registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
