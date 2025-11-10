<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador - AgilTicket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #4f46e5;
        }
        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }
        .card-stat {
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .ticket-card {
            border-left: 5px solid #2575fc;
            margin-bottom: 10px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .badge {
            font-size: 0.9em;
        }
    </style>
</head>
<body>
 
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center ms-5" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo3.png') }}" alt="Logo" width="60" height="55" class="me-2">AgilTicket</a>
            <div class="navbar-brand d-flex align-items-center ms-5">
                <div class="text-white me-3 text-end">
                    <strong>{{ Auth::user()->nombre }}</strong><br>
                    <small>{{ Auth::user()->rol->nombre ?? 'Sin rol' }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.nuevo') }}">Nuevo Ticket</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets') }}">Tickets</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.usuarios') }}">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.reportes') ? 'active' : '' }}" href="{{ route('admin.reportes') }}">Reportes</a></li>
        </ul>

        <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="card-stat">
                    <h3>{{ $pendientes }}</h3>
                    <p>Pendientes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <h3>{{ $proceso }}</h3>
                    <p>En Proceso</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <h3>{{ $resueltos }}</h3>
                    <p>Resueltos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat">
                    <h3>{{ $total }}</h3>
                    <p>Total Tickets</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Tickets Recientes</h4>
            <!-- 🔹 BOTÓN MODIFICADO -->
            <a href="{{ route('admin.tickets.nuevo') }}" class="btn btn-primary">Crear Ticket</a>
        </div>

        @foreach ($recientes as $ticket)
            <div class="ticket-card">
                <strong>{{ $ticket->radicado ?? 'Sin radicado' }}</strong> — {{ $ticket->titulo }} <br>
                <small>
                    Creado por: {{ $ticket->solicitante->nombre ?? 'Desconocido' }} • 
                    {{ $ticket->fecha_creacion ? \Carbon\Carbon::parse($ticket->fecha_creacion)->format('d/m/Y') : $ticket->created_at->format('d/m/Y') }}
                </small><br>
                <small>Asignado a: {{ $ticket->responsable->nombre ?? 'Sin asignar' }}</small>
                <div class="mt-2">
                    @php
                        $colorPrioridad = match($ticket->prioridad) {
                            'Alta' => 'danger',
                            'Media' => 'warning text-dark',
                            'Baja' => 'success',
                            default => 'secondary'
                        };

                        $nombreEstado = $ticket->estado->nombre ?? 'Sin estado';
                        $colorEstado = match($nombreEstado) {
                            'Pendiente' => 'warning text-dark',
                            'En Proceso' => 'info text-dark',
                            'Resuelto' => 'success',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $colorPrioridad }}">{{ $ticket->prioridad }}</span>
                    <span class="badge bg-{{ $colorEstado }}">{{ $nombreEstado }}</span>
                </div>
            </div>
        @endforeach

        @if ($recientes->isEmpty())
            <div class="alert alert-info">No hay tickets recientes.</div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @if (session('radicado'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var radicado = "{{ addslashes(session('radicado')) }}";
                Swal.fire({
                    title: '✅ Ticket creado exitosamente',
                    html: '<strong>Número de radicado:</strong><br><h3>' + radicado + '</h3>',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#4f46e5',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.dashboard') }}";
                    }
                });
            });
        </script>
    @endif

</body>
</html>
