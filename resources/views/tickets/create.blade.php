@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Nuevo Ticket</h2>

    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título del Ticket *</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría *</label>
            <select name="categoria" class="form-select" required>
                <option value="">Seleccione una categoría</option>
                <option value="Soporte">Soporte</option>
                <option value="Hardware">Hardware</option>
                <option value="Software">Software</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad *</label>
            <select name="prioridad" class="form-select" required>
                <option value="Alta">Alta</option>
                <option value="Media" selected>Media</option>
                <option value="Baja">Baja</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="asignado" class="form-label">Asignar a</label>
            <input type="text" name="asignado" class="form-control">
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción Detallada *</label>
            <textarea name="descripcion" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="archivo" class="form-label">Archivos Adjuntos</label>
            <input type="file" name="archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc">
        </div>

        <button type="submit" class="btn btn-primary">Crear Ticket</button>
        <a href="{{ route('tickets.dashboard') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
