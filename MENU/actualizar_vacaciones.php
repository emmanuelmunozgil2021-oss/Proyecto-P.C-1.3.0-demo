<?php
// Esta función ahora solo asegura que el registro exista, 
// pero nunca modificará el campo 'dias_totales_disponibles' una vez creado.
function calcularYActualizar($cedula, $con) {
    
    // Verificamos si ya existe un registro para este funcionario
    $check = mysqli_query($con, "SELECT id_control FROM Control_vacaciones WHERE cedula_funcionario = '$cedula'");
    
    // Si NO existe, creamos el registro con un valor inicial de 0
    // A partir de aquí, TÚ eres quien debe editar ese número manualmente en DBeaver
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($con, "INSERT INTO Control_vacaciones (cedula_funcionario, dias_totales_disponibles) VALUES ('$cedula', 0)");
    }
}
?>