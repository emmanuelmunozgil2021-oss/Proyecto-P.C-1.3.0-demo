<?php  
// Incluye tu archivo de conexión
include('../CONEXION/conexion.php'); 
$con = conexion(); 

// Recibimos los datos del formulario de registro
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula = $_POST['cedula'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$email = $_POST['email'];
$departamento = $_POST['departamento'];

// Recibimos el tipo de usuario ('admin' o 'funcionario')
$rol = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : 'empleado'; 

// Consulta SQL usando mysqli (igual que tus otros archivos del CRUD)
$sql = "INSERT INTO Usuarios (nombre, apellido, cedula, usuario, contrasena, email, departamento, rol) 
        VALUES ('$nombre', '$apellido','$cedula', '$usuario', '$contrasena', '$email', '$departamento', '$rol')";

$query = mysqli_query($con, $sql);

if($query){
    // Redirigimos según el rol que se registró
    if($rol === 'admin') {
        Header("Location: login_admin.php");
    } else {
        Header("Location: login.php");
    }
    exit;
} else {
    echo "Error al insertar: " . mysqli_error($con);
}
?>