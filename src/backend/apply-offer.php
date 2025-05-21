<?php
session_start();
require('../../src/backend/db_conection/conection.php');
$conection = getConnection();

header('Content-Type: application/json'); // importante

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => "405 - Método no permitido."]);
    exit();
}

$studentId = $_COOKIE['student_id'] ?? null;
if (!$studentId) {
    http_response_code(401);
    echo json_encode(['error' => "Usuario no autenticado."]);
    exit();
}

if (empty($_POST['offer_id'])) {
    http_response_code(400);
    echo json_encode(['error' => "ID de oferta no recibido."]);
    exit();
}

$offerId = intval($_POST['offer_id']);

$checkQuery = $conection->prepare("SELECT id FROM applications WHERE student_id = ? AND offer_id = ?");
$checkQuery->bind_param("ii", $studentId, $offerId);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    $checkQuery->close();
    echo json_encode(['error' => "Ya te has postulado a esta oferta."]);
    exit();
}
$checkQuery->close();

$stmt = $conection->prepare("INSERT INTO applications (student_id, offer_id) VALUES (?, ?)");
$stmt->bind_param("ii", $studentId, $offerId);

if ($stmt->execute()) {
    $stmt->close();
    $conection->close();
    echo json_encode(['success' => true]);

    exit();
} else {
    $stmt->close();
    $conection->close();
    echo json_encode(['error' => "Error al postularse: " . $stmt->error]);
    exit();
}
?>
