<?php
session_start();
require('../db_conection/conection.php');

// Obtener la conexión
$conection = getConnection();

// Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido.";
    exit();
}

// Validar campos obligatorios
$required_fields = ['tax_id', 'name', 'username', 'email', 'password'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo "El campo '$field' es obligatorio.";
        exit();
    }
}

// Recoger y sanitizar datos
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

// Verificar duplicados
$checkQuery = $conection->prepare("SELECT id FROM companies WHERE email = ? OR username = ? OR tax_id = ?");
$checkQuery->bind_param("sss", $email, $username, $tax_id);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    echo "El correo, nombre de usuario o CIF ya está registrado.";
    $checkQuery->close();
    $conection->close();
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
        echo "Error al subir la imagen de perfil.";
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
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conection->close();
?>