<?php
session_start();
require('../../src/backend/db_conection/conection.php');
$conection = getConnection();

$studentId = $_COOKIE['student_id'] ?? null;

if (!$studentId) {
    header("Location: /andaFP/public/users/students/students-login.php");
    exit();
}

// obtener ruta de la imagen
$query = $conection->prepare("SELECT profile_picture FROM students WHERE id = ?");
$query->bind_param("i", $studentId);
$query->execute();
$query->bind_result($profilePicturePath);
$query->fetch();
$query->close();

// obtener solo el nombre del archivo
$imageFileName = basename($profilePicturePath);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    funca
</body>
</html>