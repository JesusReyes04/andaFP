<?php
// pillo la conexión a la base de datos
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['login_error'] = "Método no permitido.";
    header("Location: /andaFP/public/users/students/students-login.php");
    exit();
}

// limpio los datis
$identifier = trim($_POST['username']); // funciona o con el username o el email, lo importante es que alguno de los dos esté
$password = $_POST['password'];
$rememberMe = isset($_POST['rememberMe']);

$query = $conection->prepare("SELECT id, password FROM students WHERE username = ? OR email = ?");
$query->bind_param("ss", $identifier, $identifier);
$query->execute();
$query->store_result();

if ($query->num_rows === 1) {
    $query->bind_result($studentId, $hashedPassword);
    $query->fetch();

    if (password_verify($password, $hashedPassword)) {
        // guardar la sesión
        $_SESSION['student_id'] = $studentId;

        // persistencia
        if ($rememberMe) {
            setcookie("student_id", $studentId, time() + (86400 * 365), "/"); // 1 año
        } else {
            setcookie("student_id", $studentId, 0, "/"); // hasta que cierres el navegador, si cierras la pestaña sigue activo
        }

        header("Location: /andaFP/public/dashboard/students-dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Contraseña incorrecta.";
    }
} else {
    $_SESSION['login_error'] = "Usuario o correo no encontrado.";
}
header("Location: /andaFP/public/users/students/students-login.php");
exit();
?>