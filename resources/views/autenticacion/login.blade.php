<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - AgilTicket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            font-family: 'Poppins', sans-serif;
        }

        
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 4rem;
            position: relative;
            color: #fff;
            background: linear-gradient(270deg, #4f46e5, #6366f1, #7c3aed, #3b82f6);
            background-size: 800% 800%;
            animation: gradientBG 12s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .left-panel-content {
            position: relative;
            z-index: 1;
        }

        .left-panel h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
        }

        .left-panel h3 {
            font-weight: 500;
            color: #e0e7ff;
        }

        .left-panel p {
            color: #e2e8f0;
            max-width: 400px;
            margin: 1rem 0 2rem;
            line-height: 1.6;
        }

        .btn-contacto {
            background-color: #ffffff;
            color: #4f46e5;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .btn-contacto:hover {
            background-color: #e0e7ff;
            transform: translateY(-2px);
        }

        /* 🔹 Panel Derecho */
        .right-panel {
            flex: 1;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* 🔹 Tarjeta de Login */
        .login-card {
            width: 100%;
            max-width: 400px;
            background: #ffffffdd;
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            text-align: center;
            animation: fadeIn 0.7s ease;
        }

        .login-card h2 {
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 8px rgba(79, 70, 229, 0.3);
        }

        .btn-login {
            background: linear-gradient(90deg, #4f46e5, #6366f1);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #4338ca, #4f46e5);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4);
        }

        .text-muted a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }

        .toggle-password {
            cursor: pointer;
            color: #6b7280;
            position: absolute;
            right: 15px;
            top: 38px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

    <!-- PANEL IZQUIERDO -->
    <div class="left-panel">
        <div class="left-panel-content">
            <h1>AgilTicket</h1>
            <h3 class="mt-3">Bienvenidos</h3>
            <p>Gestiona tus tickets, asignaciones y flujos de trabajo de manera ágil y eficiente.</p>
            <button class="btn-contacto">Contáctenos</button>
        </div>
    </div>

    <!-- PANEL DERECHO -->
    <div class="right-panel">
        <div class="login-card">
            <h2>Iniciar Sesión</h2>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" autocomplete="off" id="loginForm">
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
    </div>

    <script>
        function togglePassword(icon) {
            const input = icon.previousElementSibling;
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
