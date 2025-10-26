@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Listado de Tickets</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->titulo }}</td>
                    <td>{{ $ticket->prioridad }}</td>
                    <td>{{ $ticket->estado }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
