<?php
session_start();
include('../CONEXION/conexion.php');
$con = conexion();

// Procesar aprobación o rechazo
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_solicitud = intval($_GET['id']);
    $accion = $_GET['accion']; // 'aprobar' o 'rechazar'
    
    if ($accion == 'aprobar') {
        $nuevo_estado = 'Aprobada';
        
        // 1. Obtener los datos de la solicitud
        $sql_sol = "SELECT id_usuario, fecha_inicio, fecha_fin, observaciones FROM peticiones_vacaciones WHERE id_peticion = $id_solicitud";
        $res_sol = mysqli_query($con, $sql_sol);
        if ($datos_sol = mysqli_fetch_assoc($res_sol)) {
            $id_usuario = $datos_sol['id_usuario'];
            $observaciones = $datos_sol['observaciones'];
            
            // Verificamos si es un reclamo de días por antigüedad o una solicitud común de fechas
            if (strpos($observaciones, 'Reclamo de días por antigüedad') !== false) {
                // Extraer el número de días del texto del reclamo
                preg_match('/\((\d+)\s*días\)/', $observaciones, $matches);
                $dias_a_sumar = isset($matches[1]) ? intval($matches[1]) : 0;
                
                // Asegurar que existe registro en Control_vacaciones
                $sql_control = "SELECT * FROM Control_vacaciones WHERE id_usuario = $id_usuario";
                $res_control = mysqli_query($con, $sql_control);
                
                if (mysqli_num_rows($res_control) > 0) {
                    // Sumamos a los días disponibles totales
                    $update_vac = "UPDATE Control_vacaciones 
                                    SET dias_totales_disponibles = dias_totales_disponibles + $dias_a_sumar 
                                    WHERE id_usuario = $id_usuario";
                    mysqli_query($con, $update_vac);
                } else {
                    // Si no tiene registro previo, lo creamos asignándole estos días
                    $insert_vac = "INSERT INTO Control_vacaciones (id_usuario, dias_totales_disponibles, dias_disfrutados) 
                                    VALUES ($id_usuario, $dias_a_sumar, 0)";
                    mysqli_query($con, $insert_vac);
                }
            } else {
                // Si es una solicitud normal de fechas de vacaciones, se descuentan/registran como disfrutados
                $f_inicio = new DateTime($datos_sol['fecha_inicio']);
                $f_fin = new DateTime($datos_sol['fecha_fin']);
                $intervalo = $f_inicio->diff($f_fin);
                $dias_solicitados = $intervalo->days + 1;
                
                $sql_control = "SELECT * FROM Control_vacaciones WHERE id_usuario = $id_usuario";
                $res_control = mysqli_query($con, $sql_control);
                
                if (mysqli_num_rows($res_control) > 0) {
                    $update_vac = "UPDATE Control_vacaciones 
                                    SET dias_disfrutados = dias_disfrutados + $dias_solicitados, 
                                        dias_totales_disponibles = dias_totales_disponibles - $dias_solicitados 
                                    WHERE id_usuario = $id_usuario";
                    mysqli_query($con, $update_vac);
                }
            }
        }
    } else {
        $nuevo_estado = 'Rechazada';
    }
    
    // 4. Actualizar el estado de la petición
    $update = "UPDATE peticiones_vacaciones SET estado_peticion = '$nuevo_estado' WHERE id_peticion = $id_solicitud";
    mysqli_query($con, $update);
    
    header("Location: gestionar_vacaciones.php");
    exit();
}

// Consultas para las métricas del dashboard y contador de pendientes
$sql_pendientes_count = "SELECT COUNT(*) as total FROM peticiones_vacaciones WHERE estado_peticion = 'Pendiente'";
$res_p_count = mysqli_query($con, $sql_pendientes_count);
$total_pendientes = mysqli_fetch_assoc($res_p_count)['total'];

$sql_usuarios_count = "SELECT COUNT(*) as total FROM Usuarios";
$res_u_count = mysqli_query($con, $sql_usuarios_count);
$total_usuarios = mysqli_fetch_assoc($res_u_count)['total'];

// Consultar las solicitudes pendientes uniendo la tabla Usuarios
$query = "SELECT s.*, u.nombre, u.apellido, u.cedula 
            FROM peticiones_vacaciones s 
            JOIN Usuarios u ON s.id_usuario = u.id_usuario 
            WHERE s.estado_peticion = 'Pendiente' 
            ORDER BY s.fecha_solicitud ASC";
$resultado = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Solicitudes - Protección Civil</title>
    <link href="../CSS/style_login.css" rel="stylesheet">
    <link href="../CSS/style.css" rel="stylesheet">
    <link href="../CSS/estilos2.css" rel="stylesheet">
    <link href="../CSS/dashboard.css" rel="stylesheet">
</head>
<body>

    <!-- Menú Lateral del Dashboard -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span>🛡️ PROTECCIÓN CIVIL</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php">🏠 Inicio</a></li>
            <li class="active"><a href="gestionar_vacaciones.php">📅 Solicitudes (<?= $total_pendientes ?>)</a></li>
        </ul>
    </aside>

    <!-- Contenedor Derecho General -->
    <div class="main-wrapper">
        
        <!-- Barra Superior (Topbar) -->
        <header class="topbar">
            <div class="topbar-left">Panel de Solicitudes</div>
            <div class="topbar-right">
                <span class="topbar-left">Hola, <b><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Admin'; ?></b></span>
                <a href="../LOGIN/logout.php" class="btn-logout-top">Cerrar Sesión</a>
            </div>
        </header>

        <!-- Contenido Principal del Dashboard -->
        <main class="content">
            
            <h2 class="dashboard-title">Gestión de Vacaciones</h2>


            <!-- Sección de la Tabla de Solicitudes Pendientes -->
            <section class="seccion-tabla">
                <div class="tarjeta card-table">
                    <h3>Solicitudes Pendientes de Revisión</h3>
                    <div class="tabla-responsiva">
                        <table>
                            <thead>
                                <tr>
                                    <th>Funcionario</th>
                                    <th>Cédula</th>
                                    <th>Detalle / Fechas</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($resultado) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                                    <tr>
                                        <td><?= $row['nombre'] . " " . $row['apellido'] ?></td>
                                        <td><?= $row['cedula'] ?></td>
                                        <td>
                                            <?php if (!empty($row['observaciones']) && strpos($row['observaciones'], 'Reclamo') !== false): ?>
                                                <strong><?= $row['observaciones'] ?></strong>
                                            <?php else: ?>
                                                Del <?= $row['fecha_inicio'] ?> al <?= $row['fecha_fin'] ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $row['fecha_solicitud'] ?></td>
                                        <td>
                                            <a href="gestionar_vacaciones.php?accion=aprobar&id=<?= $row['id_peticion'] ?>" class="boton" style="background-color: #28a745; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px;" onclick="return confirm('¿Deseas aprobar esta solicitud?');">Aprobar</a>
                                            <a href="gestionar_vacaciones.php?accion=rechazar&id=<?= $row['id_peticion'] ?>" class="boton boton-eliminar" style="padding: 6px 12px; text-decoration: none; border-radius: 4px;" onclick="return confirm('¿Deseas rechazar esta solicitud?');">Rechazar</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px; color: #777;">No hay solicitudes de vacaciones pendientes en este momento.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>

        <!-- Pie de página -->
        <footer>
            Copyright © 2026 Protección Civil. Todos los derechos reservados. | Versión 1.0
        </footer>

    </div>

</body>
</html>