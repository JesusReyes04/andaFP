<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

// Verificar que la empresa esté logueada
if (!isset($_SESSION['company_id'])) {
    $_SESSION['register_error'] = "405 - Debes iniciar sesión.";
    header("Location: /andaFP/public/users/companies/companies-register.php");
    exit();
}

$companyId = $_SESSION['company_id'];

$tax_id = trim($_POST['tax_id']);
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$city = trim($_POST['city']);
$province = trim($_POST['province']);
$sector = trim($_POST['sector']);
$description = trim($_POST['description']);
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$profile_picture = $_FILES['profile_picture'] ?? null;

// Verificar que las contraseñas coincidan
if ($password !== '' && $password !== $password_confirm) {
    $_SESSION['register_error'] = "Las contraseñas no coinciden.";
    header("Location: /andaFP/public/users/companies/companies-settings.php");
    exit();
}

// Obtener la imagen actual si no se sube una nueva
$query = $conection->prepare("SELECT profile_picture FROM companies WHERE id = ?");
$query->bind_param("i", $companyId);
$query->execute();
$query->bind_result($current_picture);
$query->fetch();
$query->close();

$new_picture = $current_picture;

// Si se sube nueva imagen, procesarla
if ($profile_picture && $profile_picture['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (in_array($profile_picture['type'], $allowedTypes)) {
        $extension = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
        $fileName = uniqid("company_", true) . "." . $extension;
        $uploadPath = "C:/xampp/htdocs/andaFP/src/frontend/profile-image/" . $fileName;

        if (move_uploaded_file($profile_picture['tmp_name'], $uploadPath)) {
            $new_picture = $fileName;
        } else {
            $_SESSION['register_error'] = "Error al subir la nueva imagen.";
            header("Location: /andaFP/public/users/companies/companies-settings.php");
            exit();
        }
    } else {
        $_SESSION['register_error'] = "Tipo de archivo no permitido. Usa JPG o PNG.";
        header("Location: /andaFP/public/users/companies/companies-settings.php");
        exit();
    }
}

// Preparar y ejecutar la actualización
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = $conection->prepare("UPDATE companies SET tax_id=?, name=?, username=?, email=?, password=?, phone=?, city=?, province=?, sector=?, description=?, profile_picture=? WHERE id=?");
    $query->bind_param("sssssssssssi", $tax_id, $name, $username, $email, $hashedPassword, $phone, $city, $province, $sector, $description, $new_picture, $companyId);
} else {
    $query = $conection->prepare("UPDATE companies SET tax_id=?, name=?, username=?, email=?, phone=?, city=?, province=?, sector=?, description=?, profile_picture=? WHERE id=?");
    $query->bind_param("ssssssssssi", $tax_id, $name, $username, $email, $phone, $city, $province, $sector, $description, $new_picture, $companyId);
}

if ($query->execute()) {
    $_SESSION['success_message'] = "Datos actualizados correctamente.";
} else {
    $_SESSION['register_error'] = "Error al actualizar los datos.";
}

$query->close();
header("Location: /andaFP/public/dashboard/companies-dashboard.php");
exit();
