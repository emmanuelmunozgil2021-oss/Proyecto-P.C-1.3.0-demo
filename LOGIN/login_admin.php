<?php session_start();?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../CSS/style_login.css" rel="stylesheet">
    <title>Acceso Administrador - Protección Civil</title>
</head>
<body class="login-body">

    <nav class="navbar">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
            <span class="brand-name">Proteccion_Civil</span>
        </div>
        <div class="nav-links">
            <a href="login.php" class="login-link">Iniciar Sesión</a>
            <a href="seleccion_rol.php" class="register-btn">Registrarse</a>
        </div>
    </nav>

    <!-- Contenedor que centra la tarjeta -->
    <div class="login-wrapper">
        <div class="login-card">
            <h2>Panel de Configuración</h2>
            <p>Ingrese sus credenciales de administrador</p>
            
            <form action="validar_login_admin.php" method="POST">
                <div class="campo">
                    <label>Usuario</label>
                    <input type="text" name="usuario" placeholder="admin" required autocomplete="off">
                </div>
                <div class="campo">
                    <label>Contraseña</label>
                    <input type="password" name="contrasena" placeholder="••••••••" required>
                </div>
                <button type="submit" class="boton-azul-personal">Iniciar Sesión </button>

            
                <?php if (isset($_SESSION['error_login'])): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-top: 15px; margin-bottom: 15px; text-align: center; border: 1px solid #f5c6cb;">
                        ⚠️ <?php echo $_SESSION['error_login']; ?>
                    </div>
                    <?php 
                        // Esto borra el mensaje inmediatamente después de mostrarlo una vez
                        unset($_SESSION['error_login']); 
                    ?>
                <?php endif; ?>
        </form>

            <hr class="login-divider">

            <div class="admin-container">
                <a href="login.php" class="boton-admin" style="text-decoration: none;">Volver al Acceso de Personal</a>
            </div>
        </div>
    </div>



</body>
</html>