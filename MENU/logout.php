<?php
// logout.php
session_start();
session_unset();   // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión
header("Location: ../LOGIN/login.php"); // Redirige al login
exit();
?>