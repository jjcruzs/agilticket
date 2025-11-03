@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Editar Responsable - Ticket #{{ $ticket->id }}</h2>
        <a href="{{ route('admin.tickets') }}" class="btn btn-purple">← Volver</a>
    </div>

    <div class="card shadow-sm p-4 mb-4 border-0 rounded-4">
        <h4 class="mb-3">{{ $ticket->titulo }}</h4>
        <p class="text-muted mb-1"><strong>Solicitante:</strong> {{ $ticket->solicitante->nombre }}</p>
        <p><strong>Responsable actual:</strong> {{ $ticket->responsable->nombre ?? 'Sin asignar' }}</p>
    </div>

    <div class="card shadow-sm p-4 border-0 rounded-4">
        <h5 class="fw-semibold mb-3">Asignar Nuevo Responsable</h5>
        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
            @csrf
            @method('PUT') 

            <div class="mb-3">
                <label for="responsable_id" class="form-label">Seleccionar Responsable</label>
                <select name="responsable_id" id="responsable_id" class="form-select" required>
                    <option value="">Seleccione un responsable</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ $ticket->responsable_id == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success rounded-pill px-4">Guardar</button>
        </form>
    </div>
</div>
@endsection

