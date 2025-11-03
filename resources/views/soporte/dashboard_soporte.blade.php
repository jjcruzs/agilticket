@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-dark mb-4">🎧 Panel de Soporte</h2>

    <!-- Estadísticas rápidas -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold text-secondary">Pendientes</h6>
                    <h4 class="fw-bold text-warning">{{ $pendientes }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold text-secondary">En Proceso</h6>
                    <h4 class="fw-bold text-primary">{{ $enProceso }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold text-secondary">Resueltos</h6>
                    <h4 class="fw-bold text-success">{{ $resueltos }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold text-secondary">Total</h6>
                    <h4 class="fw-bold text-dark">{{ $total }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets recientes -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white fw-semibold">
            Tickets Recientes
        </div>
        <div class="card-body">
            @if ($ticketsRecientes->isEmpty())
                <p class="text-muted mb-0">No hay tickets recientes.</p>
            @else
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Asunto</th>
                            <th>Solicitante</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketsRecientes as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->asunto }}</td>
                                <td>{{ $ticket->solicitante->nombre ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge 
                                        @if($ticket->estado_id == 1) bg-warning 
                                        @elseif($ticket->estado_id == 2) bg-primary 
                                        @elseif($ticket->estado_id == 3) bg-success 
                                        @else bg-secondary @endif">
                                        {{ $ticket->estado->nombre ?? 'Desconocido' }}
                                    </span>
                                </td>
                                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
