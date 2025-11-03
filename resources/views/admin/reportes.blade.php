@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Reportes de Tickets</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-purple">← Volver</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reportes.generar') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Desde</label>
                    <input type="date" name="desde" class="form-control" value="{{ old('desde', $desde) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="hasta" class="form-control" value="{{ old('hasta', $hasta) }}" required>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Generar</button>

                    <button type="submit" name="export" value="csv" class="btn btn-outline-primary">Exportar CSV</button>
                </div>

                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-0">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(isset($tickets) && $tickets->isNotEmpty())
        <div class="mb-3">
            <strong>Resultados:</strong>
            <span class="ms-3">Periodo: <strong>{{ $desde }}</strong> → <strong>{{ $hasta }}</strong></span>
            <span class="ms-3">Total: <strong>{{ $totales['total'] ?? $tickets->count() }}</strong></span>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <h4 class="mb-0">{{ $totales['pendientes'] ?? 0 }}</h4>
                    <p class="mb-0">Pendientes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <h4 class="mb-0">{{ $totales['enProceso'] ?? 0 }}</h4>
                    <p class="mb-0">En Proceso</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <h4 class="mb-0">{{ $totales['resueltos'] ?? 0 }}</h4>
                    <p class="mb-0">Resueltos</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Solicitante</th>
                            <th>Responsable</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td><a href="{{ route('tickets.show', $t->id) }}">{{ $t->titulo }}</a></td>
                                <td>{{ $t->solicitante->nombre ?? '-' }}</td>
                                <td>{{ $t->responsable->nombre ?? '-' }}</td>
                                <td>{{ $t->prioridad }}</td>
                                <td>{{ $t->estado->nombre ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->fecha_creacion)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif(isset($tickets))
        <div class="alert alert-info">No se encontraron tickets en ese rango.</div>
    @endif
</div>

<style>
.card-stat { background: linear-gradient(90deg,#6a11cb,#2575fc); color: #fff; border-radius: .5rem; }
</style>
@endsection
