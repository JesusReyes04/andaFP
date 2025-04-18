<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_error'] = http_response_code(405) . " - Método no permitido.";
    header("Location: /andaFP/public/users/students/students-register.php");
    exit();
}

// mirar si está todo lo que se necesita
$required_fields = ['first_name', 'last_name', 'username', 'email', 'password', 'educational_center'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['register_error'] = "El campo '$field' es obligatorio.";
        header("Location: /andaFP/public/users/students/students-register.php");
        exit();
    }
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = "El formato del email no es válido.";
    header("Location: /andaFP/public/users/students/students-register.php");
    exit();
}

// limpiar los datos
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

// mirar que las cosas no estén diplicadas
$checkQuery = $conection->prepare("SELECT id FROM students WHERE email = ? OR username = ?");
$checkQuery->bind_param("ss", $email, $username);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    $_SESSION['register_error'] = "El correo electrónico o nombre de usuario ya está registrado.";
    $checkQuery->close();
    header("Location: /andaFP/public/users/students/students-register.php");
    exit();
}

$checkQuery->close();

$profileImagePath = null;
$cvPath = null;

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    $profileImageDir = "C:/xampp/htdocs/andaFP/src/frontend/profile-image/";
    $profileImageName = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
    $profileImagePath = $profileImageDir . $profileImageName;

    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profileImagePath)) {
        $_SESSION['register_error'] = "Error al subir la imagen de perfil.";
        header("Location: /andaFP/public/users/students/students-register.php");
        exit();
    }
}

if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
    $cvDir = "C:/xampp/htdocs/andaFP/src/frontend/cv/";
    $cvName = uniqid() . "_" . basename($_FILES['cv']['name']);
    $cvPath = $cvDir . $cvName;

    if (!move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath)) {
        $_SESSION['register_error'] = "Error al subir el CV.";
        header("Location: /andaFP/public/users/students/students-register.php");
        exit();
    }
}

// meterlo a la base de datos
$stmt = $conection->prepare("INSERT INTO students (
    first_name, last_name, username, email, phone, city, province,
    specialty, password, cv, educational_center, profile_picture
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "ssssssssssss",
    $first_name,
    $last_name,
    $username,
    $email,
    $phone,
    $city,
    $province,
    $specialty,
    $password,
    $cvPath,
    $educational_center,
    $profileImagePath
);

if ($stmt->execute()) {
    setcookie("student_id", $stmt->insert_id, 0, "/");
    unset($_SESSION['register_error']);
    header("Location: /andaFP/public/dashboard/students-dashboard.php");
    exit();
} else {
    $_SESSION['register_error'] = "Error al registrar: " . $stmt->error;
    header("Location: /andaFP/public/users/students/students-register.php");
    exit();
}

$stmt->close();
$conection->close();