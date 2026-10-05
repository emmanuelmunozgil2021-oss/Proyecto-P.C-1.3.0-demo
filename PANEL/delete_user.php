<?php
include ('../CONEXION/conexion.php');
$con = conexion();

$id = $_GET['id'];

$sql = "DELETE FROM Usuarios WHERE id_usuario = '$id'";

$query = mysqli_query($con, $sql);

if($query){
    Header("Location: index.php");
};

?>