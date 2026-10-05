<?php
session_start(); // Mantenemos tu sesión original
include('../CONEXION/conexion.php');
$con = conexion();

// Consulta para contar cuántas solicitudes tienen estado 'Pendiente' en la tabla correcta
$sql_pendientes = "SELECT COUNT(*) as total FROM peticiones_vacaciones WHERE estado_peticion = 'Pendiente'";
$res_pendientes = mysqli_query($con, $sql_pendientes);
$row_pendientes = mysqli_fetch_assoc($res_pendientes);
$total_pendientes = $row_pendientes['total'];

// Consulta total de usuarios para las tarjetas del dashboard
$sql_usuarios = "SELECT COUNT(*) as total FROM Usuarios";
$res_usuarios = mysqli_query($con, $sql_usuarios);
$total_usuarios = mysqli_fetch_assoc($res_usuarios)['total'];

$sql = "SELECT * FROM Usuarios";
$query = mysqli_query($con, $sql);

if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_solicitud = intval($_GET['id']);
    $accion = $_GET['accion'];
    
    if ($accion == 'eliminar') {
        $delete = "DELETE FROM peticiones_vacaciones WHERE id_peticion = $id_solicitud";
        mysqli_query($con, $delete);
    }
    // ... (tus demás condiciones de aprobar/rechazar)
    
    header("Location: gestionar_vacaciones.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../CSS/style.css" rel="stylesheet">
    <link href="../CSS/style_login.css" rel="stylesheet">
    <link href="../CSS/dashboard.css" rel="stylesheet">
    <title>Gestión de Usuarios - Protección Civil</title>
</head>
<body>

    <!-- Menú Lateral del Dashboard -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span>🛡️ PROTECCIÓN CIVIL</span>
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="index.php">🏠 Inicio</a></li>
            <li><a href="gestionar_vacaciones.php">📅 Solicitudes (<?= $total_pendientes ?>)</a></li>
        </ul>
    </aside>

    <!-- Contenedor Derecho General -->
    <div class="main-wrapper">
        
        <!-- Barra Superior (Topbar) -->
        <header class="topbar">
            <div class="topbar-left">Panel Administrativo</div>
            <div class="topbar-right">
                <span class="topbar-left">Hola, <b><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Admin'; ?></b></span>
                <a href="../LOGIN/logout.php" class="btn-logout-top">Cerrar Sesión</a>
            </div>
        </header>

        <!-- Contenido Principal del Dashboard -->
        <main class="content">
            
            <h2 class="dashboard-title">Dashboard General</h2>

            <!-- Fila de Tarjetas Estilo Métrica -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-icon bg-blue">👤</div>
                    <div class="metric-info">
                        <span class="metric-title">Usuarios</span>
                        <span class="metric-value"><?php echo $total_usuarios; ?></span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon bg-red">📋</div>
                    <div class="metric-info">
                        <span class="metric-title">Pendientes</span>
                        <span class="metric-value"><?php echo $total_pendientes; ?></span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon bg-green">✅</div>
                    <div class="metric-info">
                        <span class="metric-title">Sistema</span>
                        <span class="metric-value">Activo</span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon bg-yellow">⚡</div>
                    <div class="metric-info">
                        <span class="metric-title">Versión</span>
                        <span class="metric-value">v1.0</span>
                    </div>
                </div>
            </div>

            <!-- Sección de Gestión de Vacaciones (Tu bloque original adaptado) -->
            <section class="seccion-tabla" style="margin-bottom: 25px;">
                <div class="tarjeta card-table" style="padding: 20px;">
                    <h2>Gestión de Vacaciones</h2>
                    <?php if ($total_pendientes > 0): ?>
                        <p style="margin-top: 10px;">Tienes <strong><?php echo $total_pendientes; ?></strong> solicitud(es) pendiente(s) de revisión.</p>
                        <a href="gestionar_vacaciones.php" class="boton boton-editar" style="background-color: #d9534f; border: none; display: inline-block; margin-top: 10px; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px;">Gestionar Solicitudes</a>
                    <?php else: ?>
                        <p style="color: #28a745; margin-top: 10px;">No hay nuevas solicitudes de vacaciones pendientes.</p>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Tabla de Usuarios Registrados (Tu bloque original intacto) -->
            <section class="seccion-tabla">
                <div class="tarjeta card-table">
                    <h2>Usuarios Registrados</h2>
                    <div class="tabla-responsiva">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Cedula</th>
                                    <th>Usuario</th>
                                    <th>Password</th>
                                    <th>Email</th>
                                    <th>Departamento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_array($query)): ?>
                                <tr>
                                    <td><?= $row['id_usuario'] ?></td>
                                    <td><?= $row['nombre'] ?></td>
                                    <td><?= $row['apellido'] ?></td>
                                    <td><?= $row['cedula'] ?></td>
                                    <td><?= $row['usuario'] ?></td>
                                    <td><?= $row['contrasena'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['departamento'] ?></td>
                                    <td>
                                        <a href="update.php?id=<?= $row['id_usuario'] ?>" class="boton boton-editar">Editar</a>
                                        <a href="delete_user.php?id=<?= $row['id_usuario'] ?>" class="boton boton-eliminar" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>    
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