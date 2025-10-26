@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-5">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0">Crear Nuevo Ticket</h3>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-purple">← Volver</a>
        </div>

        <p class="text-muted mb-4">
            Complete la información para crear un nuevo ticket de soporte.
        </p>

        <!-- Formulario -->
        <form id="formNuevoTicket" action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Título del Ticket *</label>
                    <input type="text" name="titulo" class="form-control" placeholder="Ej: Problema con internet en oficina central" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Categoría *</label>
                    <select name="categoria_id" class="form-select" required>
                        <option value="">Seleccione una categoría</option>
                        <option value="1">Hardware</option>
                        <option value="2">Software</option>
                        <option value="3">Red</option>
                        <option value="4">Soporte General</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prioridad *</label>
                    <select name="prioridad" class="form-select" required>
                        <option value="Baja">Baja</option>
                        <option value="Media" selected>Media</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Asignar a</label>
                    <select name="responsable_id" class="form-select">
                        <option value="">Sin asignar</option>
                        @foreach($usuarios ?? [] as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Descripción Detallada *</label>
                <textarea name="descripcion" class="form-control" rows="5" placeholder="Describa el problema o solicitud en detalle..." required></textarea>
            </div>

            <div class="mb-5">
                <label class="form-label fw-semibold">Archivos Adjuntos</label>
                <div class="border-dashed rounded-3 p-4 text-center bg-light">
                    <input type="file" name="archivo" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <small class="text-muted">
                        Formatos permitidos: PDF, JPG, PNG, DOC (Máx. 10 MB)
                    </small>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <button type="button" id="btnResetForm" class="btn btn-danger px-4">Borrar</button>
                <input type="hidden" name="estado_id" value="1">
                <button type="submit" class="btn btn-purple px-4">Crear Ticket</button>
            </div>
        </form>
    </div>
</div>

<!-- 🔧 Script para limpiar formulario -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnReset = document.getElementById("btnResetForm");
        const form = document.getElementById("formNuevoTicket");

        if (btnReset && form) {
            btnReset.addEventListener("click", function () {
                if (confirm("¿Deseas borrar todos los campos del formulario?")) {
                    form.reset();

                    // Limpia también campos de archivos (Safari/Chrome fix)
                    const inputsFile = form.querySelectorAll('input[type="file"]');
                    inputsFile.forEach(input => {
                        input.value = null;
                    });
                }
            });
        }
    });
</script>
@endsection


