@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Historial de Tickets</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-purple">← Volver</a>
    </div>


    <form method="GET" action="{{ route('admin.tickets') }}" class="mb-4 d-flex align-items-center">
        <label for="estado_id" class="form-label me-2 fw-semibold">Filtrar por estado:</label>
        <select name="estado_id" id="estado_id" class="form-select w-auto me-2 rounded-3">
            <option value="">Todos</option>
            @foreach($estados as $estado)
                <option value="{{ $estado->id }}" 
                    {{ request('estado_id') == $estado->id ? 'selected' : '' }}>
                    {{ $estado->nombre }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-purple rounded-pill px-4">Filtrar</button>

        @if(request()->filled('estado_id'))
            <a href="{{ route('admin.tickets') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Quitar filtro</a>
        @endif
    </form>

    <div class="card shadow-sm border-0 rounded-4 p-4">
        <table class="table align-middle">
            <thead>
                <tr class="text-center text-secondary">
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
                    <tr class="text-center">
                        <td>#{{ $ticket->id }}</td>
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
                        <td>{{ $ticket->solicitante->nombre ?? 'Desconocido' }}</td>
                        <td>{{ $ticket->responsable->nombre ?? 'Sin asignar' }}</td>
                        <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</td>


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
                        <td colspan="8" class="text-center text-muted py-4">
                            No hay tickets registrados para este filtro.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
