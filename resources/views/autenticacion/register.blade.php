<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - AgilTicket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .register-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            text-align: center;
        }

        .register-card h2 {
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
        }

        .btn-register {
            background-color: #4f46e5;
            color: white;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-register:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
        }

        .toggle-password {
            cursor: pointer;
            color: #6b7280;
            position: absolute;
            right: 15px;
            top: 38px;
        }

        .text-muted a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>Crear Cuenta</h2>

        @if ($errors->any())
            <div class="alert alert-danger text-start">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form 
            action="{{ route('register.post') }}" 
            method="POST" 
            autocomplete="off"
            id="registerForm"
        >
            @csrf

            <!-- Campo oculto para evitar sugerencias de autocompletado -->
            <input type="password" style="display:none">

            <div class="mb-3 text-start">
                <label class="form-label fw-semibold">Nombre completo</label>
                <input 
                    type="text" 
                    name="nombre" 
                    class="form-control" 
                    placeholder="Ingresa tu nombre completo"
                    required
                    autocomplete="name"
                >
            </div>

            <div class="mb-3 text-start position-relative">
                <label class="form-label fw-semibold">Correo electrónico</label>
                <input 
                    type="email" 
                    name="correo" 
                    class="form-control" 
                    placeholder="Ingresa tu correo"
                    required 
                    autocomplete="email"
                    inputmode="email"
                >
            </div>

            <div class="mb-3 text-start position-relative">
                <label class="form-label fw-semibold">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Crea una contraseña"
                    required
                    autocomplete="new-password"
                    data-lpignore="true"
                    data-form-type="other"
                >
                <span class="toggle-password" onclick="togglePassword(this)"></span>
            </div>

            <div class="mb-3 text-start position-relative">
                <label class="form-label fw-semibold">Confirmar Contraseña</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="form-control" 
                    placeholder="Confirma tu contraseña"
                    required
                    autocomplete="new-password"
                    data-lpignore="true"
                    data-form-type="other"
                >
                <span class="toggle-password" onclick="togglePassword(this)"></span>
            </div>

            <!-- 👇 Campo oculto que asigna el rol automáticamente como "Usuario" -->
            <input type="hidden" name="rol_id" value="2">

            <button type="submit" class="btn btn-register w-100 py-2">Registrarme</button>

            <p class="mt-3 text-muted">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}">Inicia sesión</a>
            </p>
        </form>
    </div>

    <script>
        function togglePassword(icon) {
            const input = icon.previousElementSibling;
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[type="password"]').forEach(el => {
                el.setAttribute('autocomplete', 'new-password');
            });
        });
    </script>
</body>
</html>
