<?php
session_start();
require('../../src/backend/db_conection/conection.php');
$conection = getConnection();

// verify POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_error'] = "405 - Método no permitido.";
    header("Location: /andaFP/public/dashboard/students-dashboard.php");
    exit();
}

// get de student id from the cookie
$studentId = $_COOKIE['student_id'] ?? null;
if (!$studentId) {
    header("Location: /andaFP/public/users/students/students-login.php");
    exit();
}

// Verify that offer_id was received and is not empty
if (empty($_POST['offer_id'])) {
    $_SESSION['register_error'] = "ID de oferta no recibido.";
    header("Location: /andaFP/public/dashboard/students-dashboard.php");
    exit();
}

$offerId = intval($_POST['offer_id']);

// check if the student has already applied for the offer
$checkQuery = $conection->prepare("SELECT id FROM applications WHERE student_id = ? AND offer_id = ?");
$checkQuery->bind_param("ii", $studentId, $offerId);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    $_SESSION['register_error'] = "Ya te has postulado a esta oferta.";
    $checkQuery->close();
    header("Location: /andaFP/public/dashboard/students-dashboard.php");
    exit();
}
$checkQuery->close();

// Insertar nueva aplicación
$stmt = $conection->prepare("INSERT INTO applications (student_id, offer_id) VALUES (?, ?)");
$stmt->bind_param("ii", $studentId, $offerId);

if ($stmt->execute()) {
    // Éxito: puedes redirigir o simplemente devolver un 200
    $stmt->close();
    $conection->close();
    http_response_code(200);
    header("Location: /andaFP/public/dashboard/students-dashboard.php");
    exit();
} else {
    $_SESSION['register_error'] = "Error al postularse: " . $stmt->error;
    $stmt->close();
    $conection->close();
    header("Location: /andaFP/public/dashboard/students-dashboard.php");
    exit();
}
?>
