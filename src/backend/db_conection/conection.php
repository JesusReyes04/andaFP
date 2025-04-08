<?php
    $host = "localhost:3310";
    $user = "root";
    $password = "";
    $database = "andafp";

    $conexion = mysqli_connect($host, $user, $password, $database) or die("Conexión fallida: " . mysqli_connect_error());
?>