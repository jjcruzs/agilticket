<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - AgilTicket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow-lg" style="width: 400px;">
        <div class="card-body">
            <h3 class="text-center mb-4">Registro de Usuario</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
    <label class="form-label">Rol</label>
    <select name="rol_id" class="form-control" required>
        @foreach($roles as $rol)
            <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
        @endforeach
    </select>
</div>

                <button type="submit" class="btn btn-primary w-100">Registrarse</button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
