@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Detalle del Ticket #{{ $ticket->id }}</h2>
        <a href="{{ route('admin.tickets') }}" class="btn btn-purple">← Volver</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
        <h4 class="mb-3">{{ $ticket->titulo }}</h4>

        <p><strong>Prioridad:</strong> 
            <span class="badge 
                @if($ticket->prioridad == 'Alta') bg-danger 
                @elseif($ticket->prioridad == 'Media') bg-warning 
                @else bg-success @endif
                px-3 py-2 rounded-pill">
                {{ ucfirst($ticket->prioridad) }}
            </span>
        </p>

        <p><strong>Estado:</strong> 
            <span class="badge 
                @if($ticket->estado->nombre == 'Pendiente') bg-info 
                @elseif($ticket->estado->nombre == 'En Proceso') bg-primary 
                @elseif($ticket->estado->nombre == 'Resuelto') bg-success 
                @else bg-secondary @endif
                px-3 py-2 rounded-pill">
                {{ $ticket->estado->nombre ?? 'Sin estado' }}
            </span>
        </p>

        <p><strong>Solicitante:</strong> {{ $ticket->solicitante->nombre ?? 'Desconocido' }}</p>
        <p><strong>Responsable:</strong> {{ $ticket->responsable->nombre ?? 'Sin asignar' }}</p>

        <div class="mt-4">
            <h5 class="fw-semibold">Descripción del problema</h5>
            <p class="text-muted" style="white-space: pre-line;">{{ $ticket->descripcion ?? 'Sin descripción' }}</p>
        </div>

        <p class="mt-3 text-secondary">
            <strong>Creado el:</strong> 
            {{ $ticket->fecha_creacion ? $ticket->fecha_creacion->timezone('America/Bogota')->format('d/m/Y H:i') : 'Fecha desconocida' }}
        </p>

        <p class="text-secondary">
            <strong>Última actualización:</strong> 
            {{ $ticket->fecha_actualizacion ? $ticket->fecha_actualizacion->timezone('America/Bogota')->format('d/m/Y H:i') : 'Fecha desconocida' }}
        </p>
    </div>

    <h4 class="mb-3">Respuestas</h4>
    @if($ticket->respuestas->count() > 0)
        @foreach($ticket->respuestas as $respuesta)
            <div class="mb-3 p-3 border rounded bg-light">
                <p><strong>{{ $respuesta->usuario->nombre }}</strong> dijo:</p>
                <p>{{ $respuesta->contenido }}</p>
                <small class="text-muted">
                    {{ $respuesta->created_at ? $respuesta->created_at->timezone('America/Bogota')->format('d/m/Y H:i') : 'Fecha desconocida' }}
                </small>
            </div>
        @endforeach
    @else
        <p class="text-muted">Aún no hay respuestas para este ticket.</p>
    @endif

    {{-- 
    <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
        <h5 class="fw-semibold mb-3">Responder al Ticket</h5>
        <form action="{{ route('tickets.responder', $ticket->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="respuesta" class="form-label">Escribe tu respuesta o seguimiento</label>
                <textarea name="respuesta" id="respuesta" class="form-control rounded-3" rows="4" placeholder="Escribe aquí la respuesta al usuario..." required></textarea>
            </div>

            <div class="mb-3">
                <label for="estado_id" class="form-label">Actualizar Estado</label>
                <select name="estado_id" id="estado_id" class="form-select rounded-3" required>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->id }}" {{ $ticket->estado_id == $estado->id ? 'selected' : '' }}>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4">Enviar respuesta</button>
        </form>
    </div>
    --}}
</div>
@endsection
