<?php

include('../CONEXION/conexion.php');
$con = conexion();

$id = $_GET['id'];

$sql = "SELECT * FROM Usuarios WHERE id_usuario = '$id'";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_array($query); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../CSS/style_update.css" rel="stylesheet">
    <link href="../CSS/style_login.css" rel="stylesheet">
    <title>Editar Usuario - Proteccion Civil</title>
</head>
<body>


        <nav class="navbar">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
            <span class="brand-name">Módulo de Gestión de Usuarios-Sistema de Control </span>
        </div>
        <div class="nav-links">
            <a href="index.php" class="register-btn">← Regresar</a>
        </div>
    </nav>


    <main class="contenedor-edicion">
        <section class="columna-formulario">
            <div class="tarjeta-formulario">
                <h2>Editar Usuario</h2>
                
                <form action="edit_user.php" method="POST">
                    
                    <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">

                    <div class="campo-grupo">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="<?= $row['nombre'] ?>" required>
                        <label for="apellido">Apellidos</label>
                        <input type="text" id="apellido" name="apellido" value="<?= $row['apellido'] ?>" required>
                        <label for="nombre">Cedula</label>
                        <input type="text" id="cedula" name="cedula" value="<?= $row['cedula'] ?>" required>
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" name="usuario" value="<?= $row['usuario'] ?>" required>
                        <label for="contrasena">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" value="<?= $row['contrasena'] ?>" required>
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="<?= $row['email'] ?>" required>
                        <label for="departamento">Departamento</label>
                        <input type="text" id="departamento" name="departamento" value="<?= $row['departamento'] ?>" required>
                    </div>

                    <button type="submit" class="boton-actualizar">Actualizar Información</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>