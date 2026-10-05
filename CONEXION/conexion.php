<?php

function conexion(){
$host = "localhost";
$user = "root";
$password = "1234";
$bd = "proteccion_civil";

$connection = mysqli_connect($host,$user,$password);

mysqli_select_db($connection, $bd);

return $connection;

};



