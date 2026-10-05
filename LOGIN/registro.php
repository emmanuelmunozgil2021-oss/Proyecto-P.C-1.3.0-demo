<?php
// Obtenemos el tipo de la URL, por defecto será 'funcionario'
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'funcionario';
$titulo = ($tipo === 'admin') ? "Registro de Administrador" : "Registro de Funcionario";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?> - Protección Civil</title>
    <link href="../CSS/style_login.css" rel="stylesheet">
    <link href="../CSS/style_registro.css" rel="stylesheet">
</head>
<body>

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

    <div class="register-card">
        <h2><?php echo $titulo; ?></h2>
        <p>Complete los datos para registrarse en el sistema</p>
        
        <form action="procesar_registro.php" method="POST">
            <!-- Campo oculto para enviar el tipo al archivo procesador -->
            <input type="hidden" name="tipo_usuario" value="<?php echo $tipo; ?>">
            
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Nombre" required>
            </div>
            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="apellido" placeholder="Apellido" required>
            </div>
            <div class="form-group">
                <label>Cédula</label>
                <input type="text" name="cedula" placeholder="Ej. 12345678" required>
            </div>
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" placeholder="Elija un nombre de usuario" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" placeholder="correo@domain.com" required>
            </div>
            <div class="form-group">
                <label>Departamento</label>
                <input type="text" name="departamento" placeholder="Departamento" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contrasena" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-registro">Registrar <?php echo ($tipo === 'admin') ? "Administrador" : "Funcionario"; ?></button>

            <div class="login-link-text">
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>
</body>
</html>