<?php
session_start();
include('../CONEXION/conexion.php');
$con = conexion();

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT * FROM Usuarios WHERE usuario = '$usuario' AND contrasena = '$contrasena'";
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);

if($row) {
    // Guardamos el usuario y su ID único en la sesión
    $_SESSION['id_usuario'] = $row['id_usuario'];
    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['rol'] = $row['rol'];
    
    header("Location: ../MENU/menu_personal.php");
    exit(); 
} else {
    session_start(); // Asegúrate de iniciar la sesión
    $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
    header("Location: login.php");
    exit();
}
?>