<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - AgilTicket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">AgilTicket</a>
        <form action="{{ route('logout') }}" method="POST" class="ms-auto">
            @csrf
            <button class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>
</nav>

<div class="container mt-4">
    <h3>Bienvenido, {{ Auth::user()->nombre }} 👋</h3>
    <p class="text-muted">Este es tu panel principal.</p>
</div>

</body>
</html>
