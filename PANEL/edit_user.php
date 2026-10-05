<?php

include('../CONEXION/conexion.php');
$con = conexion();

$id = $_POST['id_usuario'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula = $_POST['cedula'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$email = $_POST['email'];
$departamento = $_POST['departamento'];

// Especificamos las columnas donde se insertarán los datos, omitiendo id_usuario
$sql = "UPDATE Usuarios SET nombre='$nombre', apellido='$apellido', cedula='$cedula', usuario='$usuario', contrasena='$contrasena', email='$email', departamento='$departamento' WHERE id_usuario='$id'";

$query = mysqli_query($con, $sql);

if($query){
    Header("Location: index.php");
    exit;
} else {
    echo "Error al actualizar: " . mysqli_error($con);
}
?>