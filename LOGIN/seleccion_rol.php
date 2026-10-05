<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Rol - Protección Civil</title>
    <!-- Usamos tu base de estilos -->
    <link href="../CSS/style_login.css" rel="stylesheet">
    <style>
        .role-container {
            text-align: center;
            margin-top: 100px;
            padding: 40px;
        }
        .role-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: auto;
        }
        .btn-role {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }
        .btn-admin { background-color: #f75d11; }
        .btn-admin:hover { background-color: #d94a0e; }
        .btn-personal { background-color: #007bff; }
        .btn-personal:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
            <span class="brand-name">Proteccion_Civil</span>
        </div>
        <div class="nav-links">
            <a href="login.php" class="login-link">Iniciar Sesión</a>
            <a href="registro.php" class="register-btn">Registrarse</a>
        </div>
    </nav>


    <div class="role-container">
        <div class="role-box">
            <h2>Bienvenido</h2>
            <p>Seleccione su tipo de usuario para continuar:</p>
            
            <!-- Cambia tus botones por estos -->
                <a href="registro.php?tipo=admin" class="btn-role btn-admin">Soy Administrador</a>
                <a href="registro.php?tipo=funcionario" class="btn-role btn-personal">Soy Funcionario</a>
        </div>
    </div>

</body>
</html>