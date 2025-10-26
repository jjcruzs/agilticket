@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Registrar Ticket</h2>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Prioridad</label>
            <select name="prioridad" class="form-select">
                <option value="Baja">Baja</option>
                <option value="Media" selected>Media</option>
                <option value="Alta">Alta</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Solicitante</label>
            <select name="solicitante_id" class="form-select" required>
                @foreach($usuarios as $user)
                    <option value="{{ $user->id }}">{{ $user->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100">Guardar Ticket</button>
    </form>
</div>
@endsection
