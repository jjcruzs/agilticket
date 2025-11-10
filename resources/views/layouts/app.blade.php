<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nuevo Ticket - AgilTicket</title>
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
        .navbar .user-info {
            color: white;
            text-align: right;
        }
        .btn-purple {
            background-color: #4f46e5;
            color: white;
            font-weight: 500;
        }
        .btn-purple:hover {
            background-color: #3e3abf;
            color: white;
        }
        .card {
            border-radius: 15px;
        }
        .border-dashed {
            border: 2px dashed #ccc;
        }
    </style>
</head>
 
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center ms-5" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo3.png') }}" alt="Logo" width="60" height="55" class="me-2">
                AgilTicket
            </a>
            <div class="navbar-brand d-flex align-items-center ms-5">
                <div class="user-info me-3">
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
 
 
<div class="container my-5">
    @yield('content')
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>