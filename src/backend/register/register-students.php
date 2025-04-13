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
$required_fields = ['first_name', 'last_name', 'username', 'email', 'password', 'educational_center'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo "El campo '$field' es obligatorio.";
        exit();
    }
}

// Recogida y sanitización de datos
$first_name = ucfirst(strtolower(trim($_POST['first_name'])));
$last_name = ucfirst(strtolower(trim($_POST['last_name'])));
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$province = trim($_POST['province'] ?? '');
$specialty = trim($_POST['specialty'] ?? '');
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$educational_center = trim($_POST['educational_center']);

// Verificar email o username duplicados
$checkQuery = $conection->prepare("SELECT id FROM students WHERE email = ? OR username = ?");
$checkQuery->bind_param("ss", $email, $username);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    echo "El correo electrónico o nombre de usuario ya está registrado.";
    $checkQuery->close();
    $conection->close();
    exit();
}
$checkQuery->close();

// Manejo de archivos
$profileImagePath = null;
$cvPath = null;

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    $profileImageDir = "C:/xampp/htdocs/andaFP/src/frontend/profile-image/";
    $profileImageName = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
    $profileImagePath = $profileImageDir . $profileImageName;

    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profileImagePath)) {
        echo "Error al subir la imagen de perfil.";
        exit();
    }
}

if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
    $cvDir = "C:/xampp/htdocs/andaFP/src/frontend/cv/";
    $cvName = uniqid() . "_" . basename($_FILES['cv']['name']);
    $cvPath = $cvDir . $cvName;

    if (!move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath)) {
        echo "Error al subir el CV.";
        exit();
    }
}

// Insertar nuevo estudiante
$stmt = $conection->prepare("INSERT INTO students (
    first_name, last_name, username, email, phone, city, province,
    specialty, password, cv, educational_center, profile_picture
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "ssssssssssss",
    $first_name, $last_name, $username, $email, $phone, $city,
    $province, $specialty, $password, $cvPath, $educational_center, $profileImagePath
);

if ($stmt->execute()) {
    $studentId = $stmt->insert_id;
    setcookie("student_id", $studentId, 0, "/");
    header("Location: /andaFP/public/dashboard/students-dashboard.php");
    exit();
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conection->close();
?>
