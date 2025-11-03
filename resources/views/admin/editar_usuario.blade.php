@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Editar Usuario</h2>
    </div>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body">
            <form action="{{ route('admin.usuarios.actualizar', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

 
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre completo</label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $usuario->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="correo" class="form-label fw-semibold">Correo electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror"
                           value="{{ old('correo', $usuario->correo) }}" required>
                    @error('correo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-4">
                    <label for="rol_id" class="form-label fw-semibold">Rol</label>
                    <select name="rol_id" id="rol_id" class="form-select @error('rol_id') is-invalid @enderror" required>
                        <option value="">Seleccione un rol</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ $usuario->rol_id == $rol->id ? 'selected' : '' }}>
                                {{ ucfirst($rol->nombre) }}
                            </option>
                        @endforeach
                    </select>
                    @error('rol_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn" style="background-color: #6f42c1; color: white; font-weight: 500;">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection