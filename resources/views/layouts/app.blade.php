<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgilTicket</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .btn-purple { background-color: #6f42c1; color: white; }
        .btn-purple:hover { background-color: #5b34a6; color: white; }
        .border-dashed { border: 2px dashed #ccc; }
    </style>
</head>
<body>

<!-- Barra superior -->
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="#">AGILTICKET</a>

        <div class="d-flex align-items-center ms-auto">
            <div class="text-end me-3">
                <div class="fw-semibold">{{ Auth::user()->nombre ?? 'Invitado' }}</div>
                <small class="text-muted">{{ Auth::user()->rol->nombre ?? 'Sin rol' }}</small>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-danger">Cerrar sesión</button>
            </form>
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<div class="container my-5">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
