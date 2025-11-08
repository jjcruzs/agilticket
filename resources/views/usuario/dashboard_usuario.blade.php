@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard de Tickets (USUARIO)
        </h2>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Ticket
        </a>
    </div>

    <!-- Resumen de tickets -->
    <div class="row text-center mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Pendientes</h6>
                    <h3 class="text-warning fw-bold">{{ $pendientes }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">En Proceso</h6>
                    <h3 class="text-info fw-bold">{{ $enProceso }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Resueltos</h6>
                    <h3 class="text-success fw-bold">{{ $resueltos }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-dark shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total</h6>
                    <h3 class="text-dark fw-bold">{{ $total }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Crear Ticket -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-semibold">
            <i class="bi bi-pencil-square me-2"></i> Crear Ticket
        </div>
        <div class="card-body">
            <p class="text-muted">
                <strong>Propósito:</strong> Permitir al usuario registrar una solicitud o incidencia.<br>
                <strong>Flujo principal:</strong><br>
                1. El solicitante ingresa al sistema.<br>
                2. Selecciona <span class="text-primary">"Crear Ticket"</span>.<br>
                3. Completa los campos del formulario.<br>
                4. El sistema valida la información.<br>
                5. El ticket se registra con estado <span class="badge bg-warning text-dark">Pendiente</span>.<br><br>
                <strong>Salida:</strong> Confirmación del ticket con número de radicado.
            </p>
            <a href="{{ route('tickets.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Ir a Crear Ticket
            </a>
        </div>
    </div>

    <!-- Sección: Mis Tickets -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white fw-semibold">
            <i class="bi bi-search me-2"></i> Mis Tickets
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <strong>Propósito:</strong> Permitir visualizar los tickets asociados al usuario.<br>
                <strong>Flujo principal:</strong><br>
                1. El usuario accede a la opción <span class="text-primary">"Mis Tickets"</span>.<br>
                2. El sistema muestra los tickets con filtros de estado, fecha o responsable.<br>
                3. Se asigna un agente de soporte del listado disponible.<br>
                4. El usuario puede seleccionar un ticket para ver su detalle.
            </p>

            <!-- Tickets recientes -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white fw-semibold">
                    <i class="bi bi-ticket-detailed me-2"></i> Tickets Recientes
                </div>
                <div class="card-body">
                    @if($ticketsRecientes->isEmpty())
                        <div class="alert alert-info mb-0 text-center">
                            <i class="bi bi-info-circle me-2"></i> No hay tickets recientes.
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($ticketsRecientes as $ticket)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $ticket->titulo }}</strong><br>
                                        <small class="text-muted">
                                            <strong>Radicado:</strong> {{ $ticket->radicado ?? '—' }}<br>
                                            Estado: 
                                            @if($ticket->estado->nombre == 'Pendiente')
                                                <span class="badge bg-warning text-dark">{{ $ticket->estado->nombre }}</span>
                                            @elseif($ticket->estado->nombre == 'En Proceso')
                                                <span class="badge bg-info text-dark">{{ $ticket->estado->nombre }}</span>
                                            @elseif($ticket->estado->nombre == 'Resuelto')
                                                <span class="badge bg-success">{{ $ticket->estado->nombre }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $ticket->estado->nombre }}</span>
                                            @endif
                                        </small>
                                    </div>
                                    <a href="{{ route('tickets.ver', $ticket->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> 
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Módulo: Historial de Tickets con filtros -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white fw-semibold">
            <i class="bi bi-clock-history me-2"></i> Historial de Tickets
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('usuario.dashboard_usuario') }}">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="titulo" class="form-control"
                            placeholder="Buscar por título..." value="{{ $titulo }}">
                    </div>
                    <div class="col-md-3">
                        <select name="estado_id" class="form-select">
                            <option value="">Filtrar por estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}" {{ $estadoFiltro == $estado->id ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="fecha" class="form-control" value="{{ $fecha }}">
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>

            @if($historialTickets->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i> No se encontraron tickets con los filtros aplicados.
                </div>
            @else
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Radicado</th>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historialTickets as $ticket)
                            <tr>
                                <td><strong>{{ $ticket->radicado ?? '—' }}</strong></td>
                                <td>{{ $ticket->titulo }}</td>
                                <td>
                                    <span class="badge 
                                        @if($ticket->estado->nombre == 'Pendiente') bg-warning text-dark
                                        @elseif($ticket->estado->nombre == 'En Proceso') bg-info text-dark
                                        @elseif($ticket->estado->nombre == 'Resuelto') bg-success
                                        @else bg-secondary @endif">
                                        {{ $ticket->estado->nombre }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($ticket->fecha_creacion)->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('tickets.ver', $ticket->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
