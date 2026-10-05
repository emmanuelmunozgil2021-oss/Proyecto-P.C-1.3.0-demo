<?php
session_start();
include('../CONEXION/conexion.php');
$conexion_db = conexion();

// Usamos el ID del usuario logueado en la sesión
$id_usuario = $_SESSION['id_usuario'];

// Consulta adaptada a tu tabla Usuarios y Departamentos
$sql = "SELECT u.nombre, u.apellido, u.usuario, d.nombre_departamento, u.id_departamento 
        FROM Usuarios u 
        LEFT JOIN Departamentos d ON u.id_departamento = d.id_departamento
        WHERE u.id_usuario = '$id_usuario'";

$query = mysqli_query($conexion_db, $sql);
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Trabajo</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; line-height: 1.6; }
        .hoja { width: 80%; margin: auto; border: 1px solid #ccc; padding: 50px; }
        .botones { text-align: center; margin-top: 20px; }
        @media print { .botones { display: none; } }
    </style>
</head>
<body>

<div class="hoja">
    <h2 style="text-align: center;">CONSTANCIA DE TRABAJO</h2>
    <br>
    <p>Por medio de la presente, Protección Civil hace constar que el ciudadano(a): 
    <strong><?php echo $data['nombre'] . " " . $data['apellido']; ?></strong>, 
    titular del usuario/cédula <strong><?php echo $data['usuario']; ?></strong>, 
    presta servicios en nuestra institución en el departamento de 
    <strong><?php echo $data['nombre_departamento']; ?></strong>.</p>
    <br><br>
    <p style="text-align: center;">Atentamente,<br>Dirección de Recursos Humanos</p>
</div>

<div class="botones">
    <button onclick="window.print()">Imprimir / Guardar como PDF</button>
    <a href="menu_personal.php">Volver</a>
</div>

</body>
</html>