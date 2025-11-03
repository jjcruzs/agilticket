@extends('layouts.app')

@section('content')
<div class="container py-4">


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Crear Nuevo Usuario</h3>
        <a href="{{ route('admin.usuarios') }}" class="btn btn-purple">← Volver</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.usuarios.guardar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo Electrónico</label>
                    <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Rol</label>
                    <select name="rol_id" class="form-select" required>
                        <option value="">Seleccione un rol...</option>
                        <option value="1" {{ old('rol_id') == 1 ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ old('rol_id') == 2 ? 'selected' : '' }}>Usuario</option>
                        <option value="3" {{ old('rol_id') == 3 ? 'selected' : '' }}>Soporte</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-purple">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
