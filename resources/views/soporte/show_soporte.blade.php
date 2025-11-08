@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Detalle del Ticket #{{ $ticket->id }}</h3>
        <a href="{{ route('soporte.tickets') }}" class="btn btn-secondary">← Volver al listado</a>
    </div>

    {{-- Información principal del ticket --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-primary mb-3">{{ $ticket->titulo }}</h5>
            <p class="mb-2"><strong>Descripción:</strong></p>
            <p class="text-muted">{{ $ticket->descripcion }}</p>

            <div class="row mt-3">
                <div class="col-md-3">
                    <strong>Prioridad:</strong>
                    <span class="badge bg-{{ 
                        $ticket->prioridad === 'Alta' ? 'danger' : 
                        ($ticket->prioridad === 'Media' ? 'warning text-dark' : 'success')
                    }}">{{ $ticket->prioridad }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Estado:</strong>
                    <span class="badge bg-info">{{ $ticket->estado->nombre ?? 'Sin estado' }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Solicitante:</strong> {{ $ticket->solicitante->nombre ?? 'Desconocido' }}
                </div>
                <div class="col-md-3">
                    <strong>Responsable:</strong> {{ $ticket->responsable->nombre ?? 'Sin asignar' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Respuestas --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold">Respuestas</div>
        <div class="card-body">
            @forelse ($ticket->respuestas as $respuesta)
                <div class="border-bottom mb-3 pb-2">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $respuesta->usuario->nombre ?? 'Usuario desconocido' }}</strong>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($respuesta->created_at)->format('d/m/Y H:i') }}</small>
                    </div>
                    <p class="mb-1">{{ $respuesta->contenido }}</p>
                </div>
            @empty
                <p class="text-muted mb-0">No hay respuestas aún.</p>
            @endforelse
        </div>
    </div>

    {{-- Formulario para responder --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold">Agregar respuesta</div>
        <div class="card-body">
            <form action="{{ route('soporte.tickets.responder', $ticket->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="respuesta" class="form-label">Tu respuesta</label>
                    <textarea name="respuesta" id="respuesta" rows="4" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="estado_id" class="form-label">Cambiar estado</label>
                    <select name="estado_id" id="estado_id" class="form-select" required>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado->id }}" {{ $estado->id == $ticket->estado_id ? 'selected' : '' }}>
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Responder</button>
            </form>
        </div>
    </div>

</div>
@endsection
