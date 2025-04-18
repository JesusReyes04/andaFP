<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['login_error'] = "Método no permitido.";
    header("Location: /andaFP/public/users/companies/companies-login.php");
    exit();
}

// limpio los datis
$identifier = trim($_POST['username']); // funciona o con el username o el email, lo importante es que alguno de los dos esté
$password = $_POST['password'];
$rememberMe = isset($_POST['rememberMe']);

// Buscar por username o email
$query = $conection->prepare("SELECT id, password FROM companies WHERE username = ? OR email = ?");
$query->bind_param("ss", $identifier, $identifier);
$query->execute();
$query->store_result();

if ($query->num_rows === 1) {
    $query->bind_result($companyId, $hashedPassword);
    $query->fetch();

    if (password_verify($password, $hashedPassword)) {
        // Guardar sesión
        $_SESSION['company_id'] = $companyId;

        // Cookie persistente si marcó "recordarme"
        if ($rememberMe) {
            setcookie("company_id", $companyId, time() + (86400 * 365), "/"); // 1 año
        } else {
            setcookie("company_id", $companyId, 0, "/"); // hasta que cierres el navegador, si cierras la pestaña sigue activo
        }

        header("Location: /andaFP/public/dashboard/companies-dashboard.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Contraseña incorrecta.";
    }
} else {
    $_SESSION['login_error'] = "Usuario o correo no encontrado.";
}
header("Location: /andaFP/public/users/companies/companies-login.php");
exit();
?>
