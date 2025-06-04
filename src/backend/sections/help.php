<?php

session_start();
require('../db_conection/conection.php');
$conection = getConnection();

// Initialize variables
$companyId = null;
$studentId = null;
$imageFileName = null;

// Check if the user is not a student (student_id cookie not set)
if (!isset($_COOKIE['student_id'])) {
    $companyId = $_COOKIE['company_id'] ?? null;
    if (!$companyId) {
        header("Location: /andaFP/public/users/companies/companies-login.php");
        exit();
    }

    // Obtener ruta de la imagen
    $query = $conection->prepare("SELECT profile_picture FROM companies WHERE id = ?");
    $query->bind_param("i", $companyId);
    $query->execute();
    $query->bind_result($profilePicturePath);
    $query->fetch();
    $query->close();

    $imageFileName = basename($profilePicturePath);
} else {

    $studentId = $_COOKIE['student_id'] ?? null;

    if (!$studentId) {
        header("Location: /andaFP/public/users/students/students-login.php");
        exit();
    }

    $query = $conection->prepare("SELECT profile_picture, username FROM students WHERE id = ?");
    $query->bind_param("i", $studentId);
    $query->execute();
    $query->bind_result($profilePicturePath, $username);
    $query->fetch();
    $query->close();

    $imageFileName = basename($profilePicturePath);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
</head>
<body>
    
</body>
</html>