<?php
session_start();
include('../CONEXION/conexion.php');
$conexion_db = conexion();

// 1. Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}

$usuario_actual = $_SESSION['usuario'];
$id_usuario_actual = $_SESSION['id_usuario'];

// Procesar el envío del reclamo de días si el usuario hace clic en el botón correspondiente
if (isset($_POST['reclamar_dias_calculados'])) {
    $dias_a_reclamar = intval($_POST['dias_calculados']);
    $fecha_actual = date('Y-m-d');
    $observacion_reclamo = "Reclamo de días por antigüedad (" . $dias_a_reclamar . " días)";
    
    // Insertamos como una petición pendiente en la tabla de peticiones de vacaciones
    $sql_insert_reclamo = "INSERT INTO peticiones_vacaciones (id_usuario, fecha_inicio, fecha_fin, observaciones, estado_peticion, fecha_solicitud) 
                            VALUES ('$id_usuario_actual', '$fecha_actual', '$fecha_actual', '$observacion_reclamo', 'Pendiente', '$fecha_actual')";
    mysqli_query($conexion_db, $sql_insert_reclamo);
    
    header("Location: menu_personal.php?enviado=1");
    exit();
}

// 2. Consulta para obtener los datos del usuario directamente desde la tabla Usuarios
$sql_func = "SELECT id_usuario, nombre, apellido, email, departamento 
            FROM Usuarios 
            WHERE usuario = '$usuario_actual'";
$res_func = mysqli_query($conexion_db, $sql_func);
$funcionario = mysqli_fetch_assoc($res_func);

// 3. Consulta para obtener los datos de vacaciones usando el id_usuario
$sql_vac = "SELECT dias_totales_disponibles, dias_disfrutados 
            FROM Control_vacaciones 
            WHERE id_usuario = '$id_usuario_actual'";
$res_vac = mysqli_query($conexion_db, $sql_vac);
$datos_vac = mysqli_fetch_assoc($res_vac);

// Asignamos variables con un valor por defecto de 0 si no hay registro
$dias_disponibles = $datos_vac['dias_totales_disponibles'] ?? 0;
$dias_disfrutados = $datos_vac['dias_disfrutados'] ?? 0;

// 4. Consulta para las notificaciones de vacaciones del usuario actual
$sql_notis = "SELECT * FROM Solicitudes_Vacaciones WHERE id_usuario = '$id_usuario_actual' ORDER BY id DESC LIMIT 5";
$res_notis = mysqli_query($conexion_db, $sql_notis);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Protección Civil</title>
    <link rel="stylesheet" href="../CSS/estilos2.css">
    <link rel="stylesheet" href="../CSS/style_login.css">
</head>
<body>

    <header class="navbar">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
            <span class="brand-name">Proteccion_Civil</span>
        </div>
        <div class="nav-user">
            <span>Bienvenido, <?php echo $funcionario['nombre'] . " " . $funcionario['apellido']; ?></span>
            
            <!-- Botón de Notificaciones con la Campanita -->
            <div class="notif-container">
                <input type="checkbox" id="activar-notif" class="notif-toggle">
                <label for="activar-notif" class="notif-btn" title="Ver notificaciones">🔔</label>
                
                <div class="notif-dropdown">
                    <div class="notif-header">Notificaciones de Vacaciones</div>
                    <?php if (mysqli_num_rows($res_notis) > 0): ?>
                        <?php while($noti = mysqli_fetch_assoc($res_notis)): ?>
                            <div class="notif-item">
                                <p style="margin: 0 0 5px 0;">
                                    Solicitud del <strong><?= $noti['fecha_inicio'] ?></strong> al <strong><?= $noti['fecha_fin'] ?></strong>
                                </p>
                                <p style="margin: 0;">
                                    Estado: 
                                    <span class="badge-<?= $noti['estado'] ?>">
                                        <?= ucfirst($noti['estado']) ?>
                                    </span>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="notif-item" style="text-align: center; color: #777;">
                            No tienes notificaciones recientes.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </header>

    <main class="panel-container">
        
        <?php if (isset($_GET['enviado'])): ?>
            <div style="grid-column: 1 / -1; background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                ¡Tu solicitud de reclamo de días ha sido enviada al administrador con éxito!
            </div>
        <?php endif; ?>

        <section class="card profile-card">
            <h3>Datos del Funcionario</h3>
            <hr>
            <p><strong>Usuario:</strong> <?php echo $usuario_actual; ?></p>
            <p><strong>Departamento:</strong> <?php echo $funcionario['departamento'] ?? 'No asignado'; ?></p>
            <p><strong>Fecha de Ingreso:</strong> <?php echo $funcionario['fecha_ingreso'] ?? 'No registrada'; ?></p>
        </section>

        <section class="card balance-card">
            <h3>Estado de Vacaciones</h3>
            <hr>
            <div class="balance-grid">
                <div class="balance-item disponible">
                    <span class="number"><?php echo $dias_disponibles; ?></span>
                    <span class="label">Días Restantes</span>
                </div>
                <div class="balance-item disfrutado">
                    <span class="number"><?php echo $dias_disfrutados; ?></span>
                    <span class="label">Días Disfrutados</span>
                </div>
            </div>
        </section>

        <section class="card actions-card">
            <h3>Trámites Disponibles</h3>
            <hr>
            <div class="actions-grid">
                
                <a href="solicitar_vacaciones.php" class="action-box">
                    <div class="icon">📅</div>
                    <h4>Solicitar Vacaciones</h4>
                    <p>Registrar una nueva petición de días libres.</p>
                </a>

                <a href="emitir_constancia.php" class="action-box">
                    <div class="icon">📄</div>
                    <h4>Constancia de Trabajo</h4>
                    <p>Generar y descargar constancia digital.</p>
                </a>

                <div class="action-box">
                    <h4>¿Cuántos días me corresponden?</h4>
                    <form method="POST" action="menu_personal.php" class="vacation-form">
                        <input type="number" name="anos_servicio" min="0" class="form-input" placeholder="Escribe tus años de servicio" required>
                        <button type="submit" class="btn" name="calcular_vacaciones">Calcular</button>
                    </form>
                    
                    <?php
                    if (isset($_POST['calcular_vacaciones'])) {
                        $anos = intval($_POST['anos_servicio']);
                        $dias_totales_calculados = $anos * 4;
                        
                        echo "<div style='margin-top:10px; padding: 10px; background: #f8f9fa; border-radius: 4px;'>";
                        echo "<p style='margin: 0 0 8px 0;'><strong>Resultado:</strong> " . $dias_totales_calculados . " días.</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

</body>
</html>