<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_error'] = '405 - Método no permitido.';
    header("Location: /andaFP/public/dashboard/companies/create-offers.php");
    exit();
}

$companyId = $_COOKIE['company_id'] ?? null;
if (!$companyId) {
    $_SESSION['register_error'] = "No tienes permisos para editar esta oferta.";
    header("Location: /andaFP/public/users/companies/companies-login.php");
    exit();
}

$required_fields = ['offer_id', 'offer-title', 'description', 'province', 'city', 'location', 'startDate', 'schedule', 'modality'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['register_error'] = "El campo '$field' es obligatorio.";
        header("Location: /andaFP/public/dashboard/companies/edit-offer.php?id=" . urlencode($_POST['offer_id']));
        exit();
    }
}

$offerId = intval($_POST['offer_id']);
$title = trim($_POST['offer-title']);
$description = trim($_POST['description']);
$province = trim($_POST['province']);
$city = trim($_POST['city']);
$address = trim($_POST['location']);
$start_date = trim($_POST['startDate']);
$schedule = trim($_POST['schedule']);
$modality = trim($_POST['modality']);
$required_specialty = trim($_POST['specialty'] ?? '');
$end_date = trim($_POST['endDate'] ?? null);

$stmt = $conection->prepare("SELECT company_id FROM offers WHERE id = ?");
$stmt->bind_param("i", $offerId);
$stmt->execute();
$stmt->bind_result($dbCompanyId);
$stmt->fetch();
$stmt->close();

if (!$dbCompanyId || $dbCompanyId != $companyId) {
    $_SESSION['register_error'] = "No tienes permiso para editar esta oferta.";
    header("Location: /andaFP/src/frontend/components/source-not-found.html");
    exit();
}

$update = $conection->prepare("UPDATE offers SET 
    title = ?, 
    description = ?, 
    province = ?, 
    city = ?, 
    address = ?, 
    start_date = ?, 
    end_date = ?, 
    schedule = ?, 
    modality = ?, 
    required_specialty = ?
    WHERE id = ?"
);
$update->bind_param(
    "ssssssssssi",
    $title,
    $description,
    $province,
    $city,
    $address,
    $start_date,
    $end_date,
    $schedule,
    $modality,
    $required_specialty,
    $offerId
);

if ($update->execute()) {
    header("Location: /andaFP/public/dashboard/companies-dashboard.php");
    exit();
} else {
    $_SESSION['register_error'] = "Error al actualizar la oferta: " . $update->error;
    header("Location: /andaFP/public/dashboard/companies-dashboard.php");
    exit();
}

$update->close();
$conection->close();

?>