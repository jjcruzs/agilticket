@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard de Tickets</h2>

    <div class="row text-center mb-4">
        <div class="col-md-3"><h5>Pendientes: {{ $pendientes }}</h5></div>
        <div class="col-md-3"><h5>En Proceso: {{ $enProceso }}</h5></div>
        <div class="col-md-3"><h5>Resueltos: {{ $resueltos }}</h5></div>
        <div class="col-md-3"><h5>Total: {{ $total }}</h5></div>
    </div>

    <h4>Tickets Recientes</h4>
    @if($ticketsRecientes->isEmpty())
        <div class="alert alert-info">No hay tickets recientes.</div>
    @else
        <ul>
            @foreach ($ticketsRecientes as $ticket)
                <li><strong>{{ $ticket->titulo }}</strong> — {{ $ticket->estado }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Nuevo Ticket</a>
</div>
@endsection
