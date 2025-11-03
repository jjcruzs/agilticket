<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - AgilTicket</title>
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

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            text-align: center;
        }

        .login-card h2 {
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
        }

        .btn-login {
            background-color: #4f46e5;
            color: white;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-login:hover {
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
    <div class="login-card">
        <h2>Iniciar Sesión</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form 
            action="{{ route('login') }}" 
            method="POST" 
            autocomplete="off"
            id="loginForm"
        >
            @csrf


            <input type="password" style="display:none">


            <div class="mb-3 text-start position-relative">
                <label class="form-label fw-semibold">Correo</label>
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
                    placeholder="Ingresa tu contraseña"
                    required
                    autocomplete="new-password"
                    data-lpignore="true"
                    data-form-type="other"
                >
                <span class="toggle-password" onclick="togglePassword(this)"></span>
            </div>

            <button type="submit" class="btn btn-login w-100 py-2">Ingresar</button>

            <p class="mt-3 text-muted">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}">Regístrate</a>
            </p>
        </form>
    </div>

    <script>
        function togglePassword(icon) {
            const input = icon.previousElementSibling;
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const password = document.querySelector('input[name="password"]');
            password.setAttribute('autocomplete', 'new-password');
        });
    </script>
</body>
</html>
