@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Historial de Tickets</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-purple">← Volver</a>
    </div>

    <form method="GET" action="{{ route('admin.tickets') }}" class="mb-4 d-flex align-items-center gap-3 flex-wrap">
        <div>
            <label for="estado_id" class="form-label mb-1">Filtrar por Estado:</label>
            <select name="estado_id" id="estado_id" class="form-select">
                <option value="">Todos</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}" {{ $estadoFiltro == $estado->id ? 'selected' : '' }}>
                        {{ $estado->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="responsable_id" class="form-label mb-1">Filtrar por Responsable:</label>
            <select name="responsable_id" id="responsable_id" class="form-select">
                <option value="">Todos</option>
                @foreach($responsables as $responsable)
                    <option value="{{ $responsable->id }}" {{ $responsableFiltro == $responsable->id ? 'selected' : '' }}>
                        {{ $responsable->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="radicado" class="form-label mb-1">Buscar por Radicado:</label>
            <input type="text" name="radicado" id="radicado" class="form-control"
                placeholder="Ej: TCK-0003" value="{{ $radicadoFiltro }}">
        </div>

        <div class="align-self-end">
            <button type="submit" class="btn btn-purple">Filtrar</button>
            <a href="{{ route('admin.tickets') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <div class="card shadow-sm border-0 rounded-4 p-4">
        <table class="table align-middle">
            <thead>
                <tr class="text-center text-secondary">
                    <th>ID</th>
                    <th>Radicado</th>
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
                    <tr class="text-center">
                        <td>#{{ $ticket->id }}</td>
                        <td class="fw-semibold text-purple text-truncate">{{ $ticket->radicado ?? 'N/A' }}</td>
                        <td class="text-start">{{ $ticket->titulo }}</td>

                        <td>
                            <span class="badge 
                                @if($ticket->prioridad == 'Alta') bg-danger 
                                @elseif($ticket->prioridad == 'Media') bg-warning 
                                @else bg-success @endif
                                px-3 py-2 rounded-pill">
                                {{ ucfirst($ticket->prioridad) }}
                            </span>
                        </td>

                        <td>
                            @php
                                $nombreEstado = $ticket->estado->nombre ?? 'Sin estado';
                            @endphp

                            <span class="badge 
                                @if($nombreEstado == 'Pendiente') bg-info 
                                @elseif($nombreEstado == 'En Proceso') bg-primary 
                                @elseif($nombreEstado == 'Resuelto') bg-success 
                                @else bg-secondary @endif
                                px-3 py-2 rounded-pill">
                                {{ $nombreEstado }}
                            </span>
                        </td>

                        <td class="text-truncate">{{ $ticket->solicitante->nombre ?? 'Desconocido' }}</td>
                        <td class="text-truncate">{{ $ticket->responsable->nombre ?? 'Sin asignar' }}</td>
                        <td class="text-truncate">{{ \Carbon\Carbon::parse($ticket->fecha_creacion)->format('d/m/Y H:i') }}</td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('tickets.show', $ticket->id) }}" 
                                   class="btn btn-sm btn-info text-white rounded-pill px-3">
                                   Ver
                                </a>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" 
                                   class="btn btn-sm btn-warning text-white rounded-pill px-3">
                                   Editar
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No hay tickets registrados para este filtro.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.text-purple { color: #6a11cb; }
.btn-purple {
    background-color: #6a11cb;
    color: white;
    transition: all 0.2s ease;
}
.btn-purple:hover {
    background-color: #4f46e5;
}
</style>
@endsection
