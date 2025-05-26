<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_id'] ?? null;
    $offerId = $_POST['offer_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($studentId && $offerId && in_array($status, ['approved', 'rejected'])) {
        $stmt = $conection->prepare("UPDATE applications SET status = ? WHERE student_id = ? AND offer_id = ?");
        $stmt->bind_param("sii", $status, $studentId, $offerId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Estado actualizado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos o estado inválido']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>