<?php

session_start();
require('../db_conection/conection.php');
$conection = getConnection();

$studentId = $_COOKIE['student_id'] ?? null;

if (!$studentId) {
    header("Location: /andaFP/public/users/students/students-login.php");
    exit();
}

// obtener ruta de la imagen
$query = $conection->prepare("SELECT profile_picture, username FROM students WHERE id = ?");
$query->bind_param("i", $studentId);
$query->execute();
$query->bind_result($profilePicturePath, $username);
$query->fetch();
$query->close();

// obtener solo el nombre del archivo
$imageFileName = basename($profilePicturePath);

$query = $conection->prepare("
    SELECT 
        applications.id AS application_id,
        applications.status as estao,
        applications.applied_at,
        offers.*,
        companies.name AS company_name,
        companies.email AS company_email,
        companies.city AS company_city,
        companies.province AS company_province,
        companies.sector AS company_sector,
        companies.profile_picture AS company_profile_picture
    FROM 
        applications
    JOIN 
        offers ON applications.offer_id = offers.id
    JOIN 
        companies ON offers.company_id = companies.id
    WHERE 
        applications.student_id = ?
    ORDER BY 
        applications.applied_at ASC
");

$query->bind_param("i", $studentId);
$query->execute();
$result = $query->get_result();
$applications = $result->fetch_all(MYSQLI_ASSOC);
$query->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndaFP</title>
    <script src="/andaFP/public/assets/js/sidebar.js" defer></script>
    <link rel="stylesheet" href="/andaFP/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="/andaFP/public/assets/css/offers-list.css">
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
</head>

<body>
    <header class="header">
        <div class="header-container">
            <button id="menu-toggle" class="menu-btn">&#9776;</button>
            <h1 class="andafp">andaFP</h1>
            <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars($imageFileName); ?>" alt="" class="profile-pic">
        </div>
    </header>

    <aside class="sidebar" id="sidebar">
        <button class="close-btn" id="close-btn">&times;</button>
        <nav>
            <ul>
                <li><a href="/andaFP/public/dashboard/students-dashboard.php">Inicio</a></li>
                <li><a href="#">Candidaturas</a></li>
                <li><a href="#">Tus estadísticas</a></li>
                <li><a href="#">Ayuda</a></li>
                <li><a href="#">Ajustes</a></li>
                <li><a href="#">Sobre nosotros</a></li>
                <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
                <li><a href="/andaFP/src/backend/logout/students-logout.php" id="logout">Cerrar sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <?php if (empty($applications)): ?>
            <h3 class="notApliedTitle">Aun no has aplicado a ninguna oferta.</h3>
            <p class="notApliedText">¿Por qué no comenzar hoy?</p>
        <?php else: ?>
            <h2>Mis candidaturas</h2>
            <div class="card-container">
            <?php foreach ($applications as $app): ?>
                <div class="job-card">
                    <div class="job-top">
                        <?php if (!empty($app['company_profile_picture'])): ?>
                            <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars(basename($app['company_profile_picture'])); ?>" class="job-img">
                            <?php else: ?>
                                <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars(basename($app['company_profile_picture'])); ?>" class="job-img">
                        <?php endif; ?>

                        <div class="job-header">
                            <h3 class="job-title"><?php echo htmlspecialchars($app['title']); ?></h3>
                            <p class="job-company"><?php echo htmlspecialchars($app['company_name']); ?></p>
                        </div>
                    </div>

                    <div class="job-meta">
                        <span id="job-ubication"><strong class="job-data">Ubicación:</strong> <?= htmlspecialchars($app['city']) ?>, <?= htmlspecialchars($app['province']) ?></span>
                        <span id="job-modality"><strong class="job-data">Modalidad:</strong> <?= htmlspecialchars($app['modality']) ?></span>
                    </div>

                    <p class="job-description"><?php echo htmlspecialchars($app['description']); ?></p>

                    <div class="job-footer">
                        <span class="job-date"><?php echo htmlspecialchars($app['created_at']); ?></span>
                        <div class="application-status <?php echo $app['estao']?>">
                            <?php
                            switch ($app['estao']) {
                                case 'pending':
                                    echo 'Pendiente';
                                    break;
                                case 'approved':
                                    echo 'Aceptada';
                                    break;
                                case 'rejected':
                                    echo 'Rechazada';
                                    break;
                                default:
                                    echo htmlspecialchars($app['estao']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>