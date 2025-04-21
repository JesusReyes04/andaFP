<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

// compruebo el post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_error'] = '405' . " - Método no permitido.";
    header("Location: /andaFP/public/users/companies/companies-register.php");
    exit();
}

// ver si están todos los campos que son obligatorios
$required_fields = ['tax_id', 'name', 'username', 'email', 'password'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['register_error'] = "El campo '$field' es obligatorio.";
        header("Location: /andaFP/public/users/companies/companies-register.php");
        exit();
    }
}

// sanitizar datos
$tax_id = trim($_POST['tax_id']);
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$province = trim($_POST['province'] ?? '');
$sector = trim($_POST['sector'] ?? '');
$description = trim($_POST['description'] ?? '');

// mirar si hay cosas duplicadas
$checkQuery = $conection->prepare("SELECT id FROM companies WHERE email = ? OR username = ? OR tax_id = ?");
$checkQuery->bind_param("sss", $email, $username, $tax_id);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    $_SESSION['register_error'] = "El correo, nombre de usuario o CIF ya está registrado.";
    $checkQuery->close();
    $conection->close();
    header("Location: /andaFP/public/users/companies/companies-register.php");
    exit();
}
$checkQuery->close();

// Manejo de imagen de perfil
$profileImagePath = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    $profileImageDir = "C:/xampp/htdocs/andaFP/src/frontend/profile-image/";
    $profileImageName = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
    $profileImagePath = $profileImageDir . $profileImageName;

    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profileImagePath)) {
        $_SESSION['register_error'] = "Error al subir la imagen de perfil.";
        header("Location: /andaFP/public/users/companies/companies-register.php");
        exit();
    }
}

// Insertar empresa
$stmt = $conection->prepare("INSERT INTO companies (
    tax_id, name, username, email, phone, city, province, sector, description, password, profile_picture
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sssssssssss",
    $tax_id, $name, $username, $email, $phone, $city, $province,
    $sector, $description, $password, $profileImagePath
);

if ($stmt->execute()) {
    $companyId = $stmt->insert_id;
    setcookie("company_id", $companyId, 0, "/");
    header("Location: /andaFP/public/dashboard/companies-dashboard.php");
    exit();
} else {
    $_SESSION['register_error'] = "Error al registrar: " . $stmt->error;
    header("Location: /andaFP/public/users/companies/companies-register.php");
    exit();
}

$stmt->close();
$conection->close();
?>
