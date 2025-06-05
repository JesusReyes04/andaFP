<?php

session_start();
require('../db_conection/conection.php');
$conection = getConnection();

// variables that will be used to store company or student information
$companyId = null;
$studentId = null;
$imageFileName = null;

// check if the user is not a student (student_id cookie not set)
if (!isset($_COOKIE['student_id'])) {
    $companyId = $_COOKIE['company_id'] ?? null;
    if (!$companyId) {
        header("Location: /andaFP/public/users/companies/companies-login.php");
        exit();
    }

    // get the imgage path 
    $query = $conection->prepare("SELECT profile_picture FROM companies WHERE id = ?");
    $query->bind_param("i", $companyId);
    $query->execute();
    $query->bind_result($profilePicturePath);
    $query->fetch();
    $query->close();

    $imageFileName = basename($profilePicturePath);
} else {

    $studentId = $_COOKIE['student_id'] ?? null;

    if (!$studentId) {
        header("Location: /andaFP/public/users/students/students-login.php");
        exit();
    }

    $query = $conection->prepare("SELECT profile_picture, username FROM students WHERE id = ?");
    $query->bind_param("i", $studentId);
    $query->execute();
    $query->bind_result($profilePicturePath, $username);
    $query->fetch();
    $query->close();

    $imageFileName = basename($profilePicturePath);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/andaFP/public/assets/css/dashboard.css">
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
    <link rel="stylesheet" href="/andaFP/public/assets/css/about-us.css">
    <script src="/andaFP/public/assets/js/sidebar.js" defer></script>
    <title>Sobre nosotros | AndaFP</title>
</head>

<body>
    <header class="header">
        <div class="header-container">
            <button id="menu-toggle" class="menu-btn">&#9776;</button>
            <h1 class="andafp">AndaFP</h1>
            <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars($imageFileName); ?>" alt="" class="profile-pic">
        </div>
    </header>

    <aside class="sidebar" id="sidebar">
        <button class="close-btn" id="close-btn">&times;</button>
        <nav>
            <ul>
                <?php if (!isset($studentId)) : ?>
                    <!-- companies -->
                    <li><a href="/andaFP/public/dashboard/companies-dashboard.php">Inicio</a></li>
                    <li><a href="/andaFP/public/dashboard/companies/create-offers.php">Publicar ofertas</a></li>
                    <li><a href="#">Ayuda</a></li>
                    <li><a href="/andaFP/public/users/companies/companies-settings.php">Ajustes</a></li>
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
                    <li><a href="/andaFP/src/backend/logout/companies-logout.php" id="logout">Cerrar sesión</a></li>
                <?php else : ?>
                    <!-- students -->
                    <li><a href="/andaFP/public/dashboard/students-dashboard.php">Inicio</a></li>
                    <li><a href="/andaFP/src/backend/sections/applications-page.php">Candidaturas</a></li>
                    <li><a href="#">Ayuda</a></li>
                    <li><a href="/andaFP/public/users/students/students-settings.php">Ajustes</a></li>
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
                    <li><a href="/andaFP/src/backend/logout/students-logout.php" id="logout">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </aside>
    <main class="main-content" id="about">
        <h1>Sobre Nosotros</h1>

        <p><strong>AndaFP</strong> nace con una idea clara: facilitar el encuentro entre estudiantes de Formación Profesional, empresas y centros educativos de Andalucía. En un entorno donde muchas veces la búsqueda de prácticas se vuelve complicada, esta plataforma surge como una solución intuitiva, accesible y enfocada en cubrir una necesidad real de la comunidad educativa andaluza.</p>

        <p>Aunque uses el plural, detrás de AndaFP no hay un equipo, sino una sola persona. Soy <strong>Jesús Reyes Espejo</strong>, también conocido como <strong>pesstunky47</strong>, y he desarrollado íntegramente este proyecto en un periodo de 2 meses, como muestra de lo que se puede lograr con dedicación, pasión por la programación y ganas de aportar algo útil a los demás. Además este es mi proyecto de fin de grado como alumno de Desarrollo de Aplicaciones Web - 2024/2025</p>

        <p><strong>AndaFP</strong> no tiene ningún fin lucrativo. Es una contribución personal, creada con el único propósito de servir como herramienta gratuita y abierta para todos aquellos que forman parte del ecosistema de la FP: estudiantes que buscan prácticas, empresas que desean ofrecerlas y centros docentes que quieren mejorar la inserción laboral de su alumnado.</p>

        <p>Creo firmemente en el poder de la tecnología para cambiar realidades, y este proyecto es mi granito de arena para mejorar la comunidad educativa desde dentro, creando un bien para todos los elementos que intervienen en la comunidad educativa.</p>

        <h2>Contacto</h2>

        <ul style="list-style: none;">
            <li>
                <strong>Correo electrónico:</strong>
                <a href="mailto:pesstunky47@gmail.com">jesusreyesespejo04@gmail.com</a>
            </li>
            <li><a href="https://github.com/JesusReyes04" target="_blank"><strong>GitHub:</strong> github.com/JesusReyes04</a></li>
            <li><strong>Nombre completo:</strong> Jesús Reyes Espejo - pesstunky47</li>
        </ul>
    </main>

    <footer class="footer">
        <span>Proyecto Fin de Grado realizado por Jesús Reyes Espejo</span><br>
        <span>IES Kursaal, 2025.</span>
    </footer>
</body>

</html>