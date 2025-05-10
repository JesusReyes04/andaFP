<?php
session_start();
require('../db_conection/conection.php');
$conection = getConnection();

// Comprobar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['register_error'] = '405 - Método no permitido.';
    header("Location: /andaFP/public/dashboard/companies/create-offers.php");
    exit();
}

// Comprobar si el usuario (empresa) está autenticado
$companyId = $_COOKIE['company_id'] ?? null;
if (!$companyId) {
    $_SESSION['register_error'] = "No tienes permisos para crear una oferta.";
    header("Location: /andaFP/public/users/companies/companies-login.php");
    exit();
}

// Validar campos obligatorios
$required_fields = ['offer-title', 'description', 'province', 'city', 'location', 'startDate', 'schedule', 'modality'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['register_error'] = "El campo '$field' es obligatorio.";
        header("Location: /andaFP/public/dashboard/companies/create-offers.php");
        exit();
    }
}

// Sanitizar datos
$title = trim($_POST['offer-title']);
$description = trim($_POST['description']);
$province = trim($_POST['province']);
$city = trim($_POST['city']);
$address = trim($_POST['location']);
$start_date = trim($_POST['startDate']);
$schedule = trim($_POST['schedule']);
$modality = trim($_POST['modality']);
$required_specialty = trim($_POST['specialty'] ?? null);
$end_date = trim($_POST['endDate'] ?? null);

// Insertar oferta
$stmt = $conection->prepare("INSERT INTO offers (
    company_id, title, description, city, required_specialty, start_date, end_date,
    schedule, modality, province, address
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "issssssssss",
    $companyId,
    $title,
    $description,
    $city,
    $required_specialty,
    $start_date,
    $end_date,
    $schedule,
    $modality,
    $province,
    $address
);

if ($stmt->execute()) {
    $_SESSION['register_success'] = "Oferta publicada con éxito.";
    header("Location: /andaFP/public/dashboard/companies/create-offers.php");
    exit();
} else {
    $_SESSION['register_error'] = "Error al publicar la oferta: " . $stmt->error;
    header("Location: /andaFP/public/dashboard/companies/create-offers.php");
    exit();
}

$stmt->close();
$conection->close();
