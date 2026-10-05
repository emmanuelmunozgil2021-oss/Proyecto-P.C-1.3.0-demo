<?php
session_start();
include('../CONEXION/conexion.php');
$conexion_db = conexion();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conexion_db, $_POST['usuario']);
    $contrasena = mysqli_real_escape_string($conexion_db, $_POST['contrasena']);
    
    // Consultamos en la tabla unificada de Usuarios que creamos en DBeaver
    $query = "SELECT * FROM Usuarios WHERE usuario = '$usuario' AND contrasena = '$contrasena'";
    $resultado = mysqli_query($conexion_db, $query);

    if ($row = mysqli_fetch_array($resultado)) {
        
    if ($row['rol'] !== 'admin') {
            $_SESSION['error_login'] = "Error: No eres administrador, uso exclusivo para administradores.";
            header("Location: login_admin.php");
            exit();
        }

        // Guardamos los datos de la sesión si SÍ es administrador
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['rol'] = $row['rol'];
        
        // Redirección al panel de administración
        header("Location: ../PANEL/index.php");
        exit();
        
    } else {
        $_SESSION['error_login'] = "Usuario o contraseña de administrador incorrectos.";
        header("Location: login_admin.php");
        exit();
    }
}
?>