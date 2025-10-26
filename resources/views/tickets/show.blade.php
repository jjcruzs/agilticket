@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Detalle del Ticket #{{ $ticket->id }}</h3>
        <a href="{{ route('admin.tickets') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">{{ $ticket->titulo }}</h5>
            <p class="text-muted">{{ $ticket->descripcion }}</p>

            <div class="mt-4">
                <p><strong>Prioridad:</strong> {{ $ticket->prioridad }}</p>
                <p><strong>Estado actual:</strong> {{ $ticket->estado->nombre ?? 'Sin estado' }}</p>
                <p><strong>Solicitante:</strong> {{ $ticket->solicitante->nombre ?? 'Desconocido' }}</p>
                <p><strong>Responsable:</strong> {{ $ticket->responsable->nombre ?? 'Sin asignar' }}</p>
                <p><strong>Fecha de creación:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="d-flex align-items-center gap-3">
                @csrf
                @method('PUT')

                <div>
                    <label for="estado_id" class="form-label fw-semibold mb-0">Actualizar Estado:</label>
                    <select name="estado_id" id="estado_id" class="form-select">
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}" {{ $ticket->estado_id == $estado->id ? 'selected' : '' }}>
                                {{ $estado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-purple">Guardar Cambios</button>
            </form>

            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('¿Estás seguro de eliminar este ticket?')">Eliminar Ticket</button>
            </form>
        </div>
    </div>
</div>
@endsection
