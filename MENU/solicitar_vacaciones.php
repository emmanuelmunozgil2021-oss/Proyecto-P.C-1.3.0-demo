<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../LOGIN/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Vacaciones</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <link rel="stylesheet" href="../CSS/style_login.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Solicitud de Vacaciones</h1>
        </div>
        
        <div class="card">
            <form action="procesar_solicitud.php" method="POST">
                <div class="form-group">
                    <label>Fecha de Inicio:</label>
                    <input type="date" name="fecha_inicio" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label>Fecha de Fin:</label>
                    <input type="date" name="fecha_fin" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label>Observaciones:</label>
                    <textarea name="observaciones" class="form-input" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn">Enviar Solicitud</button>
                <a href="menu_personal.php" class="btn-volver">Volver al Menú</a>
            </form>
        </div>
    </div>
</body>
</html>