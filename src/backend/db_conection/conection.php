<?php
function getConnection() {
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "andafp";

    $conection = mysqli_connect($host, $user, $password, $database);

    if (!$conection) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    
    return $conection;
}
?>
