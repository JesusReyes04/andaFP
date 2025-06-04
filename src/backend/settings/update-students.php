<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

// Verificar que el estudiante esté logueado
if (!isset($_SESSION['student_id'])) {
    $_SESSION['register_error'] = "405 - Debes iniciar sesión.";
    header("Location: /andaFP/public/users/students/students-register.php");
    exit();
}

$studentId = $_SESSION['student_id'];

// Recoger y limpiar los datos
$first_name = ucfirst(strtolower(trim($_POST['first_name'] ?? '')));
$last_name = ucfirst(strtolower(trim($_POST['last_name'] ?? '')));
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$city = trim($_POST['city'] ?? '');
$province = trim($_POST['province'] ?? '');
$specialty = trim($_POST['specialty'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$educational_center = trim($_POST['educational_center'] ?? '');
$profile_picture = $_FILES['profile_picture'] ?? null;
$cv = $_FILES['cv'] ?? null;

// Validaciones básicas
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = "El formato del email no es válido.";
    header("Location: /andaFP/public/users/students/students-settings.php");
    exit();
}

if ($password !== '' && $password !== $password_confirm) {
    $_SESSION['register_error'] = "Las contraseñas no coinciden.";
    header("Location: /andaFP/public/users/students/students-settings.php");
    exit();
}

// Obtener imagen y CV actuales
$query = $conection->prepare("SELECT profile_picture, cv FROM students WHERE id = ?");
$query->bind_param("i", $studentId);
$query->execute();
$query->bind_result($current_picture, $current_cv);
$query->fetch();
$query->close();

$new_picture = $current_picture;
$new_cv = $current_cv;

// Procesar imagen de perfil si se sube una nueva
if ($profile_picture && $profile_picture['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (in_array($profile_picture['type'], $allowedTypes)) {
        $extension = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
        $fileName = uniqid("student_", true) . "." . $extension;
        $uploadPath = "C:/xampp/htdocs/andaFP/src/frontend/profile-image/" . $fileName;

        if (move_uploaded_file($profile_picture['tmp_name'], $uploadPath)) {
            $new_picture = $fileName;
        } else {
            $_SESSION['register_error'] = "Error al subir la imagen de perfil.";
            header("Location: /andaFP/public/users/students/students-settings.php");
            exit();
        }
    } else {
        $_SESSION['register_error'] = "Tipo de archivo de imagen no permitido.";
        header("Location: /andaFP/public/users/students/students-settings.php");
        exit();
    }
}

// Procesar nuevo CV si se sube uno
if ($cv && $cv['error'] === UPLOAD_ERR_OK) {
    $extension = pathinfo($cv['name'], PATHINFO_EXTENSION);
    $fileName = uniqid("cv_", true) . "." . $extension;
    $cvPath = "C:/xampp/htdocs/andaFP/src/frontend/cv/" . $fileName;

    if (move_uploaded_file($cv['tmp_name'], $cvPath)) {
        $new_cv = $fileName;
    } else {
        $_SESSION['register_error'] = "Error al subir el CV.";
        header("Location: /andaFP/public/users/students/students-settings.php");
        exit();
    }
}

// Actualizar los datos
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = $conection->prepare("UPDATE students SET 
        first_name=?, last_name=?, username=?, email=?, phone=?, city=?, province=?, specialty=?, password=?, cv=?, educational_center=?, profile_picture=?
        WHERE id=?");
    $query->bind_param("ssssssssssssi", $first_name, $last_name, $username, $email, $phone, $city, $province, $specialty, $hashedPassword, $new_cv, $educational_center, $new_picture, $studentId);
} else {
    $query = $conection->prepare("UPDATE students SET 
        first_name=?, last_name=?, username=?, email=?, phone=?, city=?, province=?, specialty=?, cv=?, educational_center=?, profile_picture=?
        WHERE id=?");
    $query->bind_param("sssssssssssi", $first_name, $last_name, $username, $email, $phone, $city, $province, $specialty, $new_cv, $educational_center, $new_picture, $studentId);
}

if ($query->execute()) {
    $_SESSION['success_message'] = "Datos actualizados correctamente.";
} else {
    $_SESSION['register_error'] = "Error al actualizar los datos.";
}

$query->close();
$conection->close();
header("Location: /andaFP/public/dashboard/students-dashboard.php");
exit();
