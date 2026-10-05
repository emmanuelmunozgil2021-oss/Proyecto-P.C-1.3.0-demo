<?php
session_start();
include('../CONEXION/conexion.php');

// 1. Validar que la sesión esté activa (Si no, redirige al login de inmediato)
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_usuario'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}

$conexion_db = conexion();
// Usamos el ID numérico real guardado en la sesión
$id_usuario_actual = $_SESSION['id_usuario'];

// 2. Recibir datos del formulario
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$observaciones = $_POST['observaciones'];
$fecha_solicitud = date('Y-m-d');

// 3. Calcular días solicitados
$inicio = new DateTime($fecha_inicio);
$fin = new DateTime($fecha_fin);
$intervalo = $inicio->diff($fin);
$dias_solicitados = $intervalo->days + 1;

// 4. CONSULTA DE SEGURIDAD: Verificar días disponibles usando 'id_usuario'
$sql_check = "SELECT dias_totales_disponibles FROM Control_vacaciones WHERE id_usuario = '$id_usuario_actual'";
$res_check = mysqli_query($conexion_db, $sql_check);
$data = mysqli_fetch_assoc($res_check);
$disponibles = $data ? $data['dias_totales_disponibles'] : 0;

// 5. Validar si tiene suficientes días
if ($dias_solicitados > $disponibles) {
    echo "<script>alert('Error: No tiene suficientes días disponibles. Saldo actual: $disponibles días. Solicitados: $dias_solicitados días.'); window.location='solicitar_vacaciones.php';</script>";
} else {
    // 6. Si tiene saldo, procedemos con la inserción usando 'id_usuario'
    $sql_insert = "INSERT INTO peticiones_vacaciones (id_usuario, fecha_solicitud, fecha_inicio, fecha_fin, estado_peticion, observaciones) 
            VALUES ('$id_usuario_actual', '$fecha_solicitud', '$fecha_inicio', '$fecha_fin', 'Pendiente', '$observaciones')";

    if (mysqli_query($conexion_db, $sql_insert)) {
        // Descontar días actualizando por 'id_usuario'
        $update_sql = "UPDATE Control_vacaciones 
                        SET dias_totales_disponibles = dias_totales_disponibles - $dias_solicitados,
                            dias_disfrutados = dias_disfrutados + $dias_solicitados
                        WHERE id_usuario = '$id_usuario_actual'";
        mysqli_query($conexion_db, $update_sql);

        echo "<script>alert('Solicitud enviada correctamente. Se han descontado $dias_solicitados días.'); window.location='menu_personal.php';</script>";
    } else {
        echo "Error al procesar la solicitud: " . mysqli_error($conexion_db);
    }
}
?>