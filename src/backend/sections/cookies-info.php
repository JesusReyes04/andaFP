<?php

session_start();
require('../db_conection/conection.php');
$conection = getConnection();

// Initialize variables
$companyId = null;
$studentId = null;
$imageFileName = null;

// Check if the user is not a student (student_id cookie not set)
if (!isset($_COOKIE['student_id'])) {
    $companyId = $_COOKIE['company_id'] ?? null;
    if (!$companyId) {
        header("Location: /andaFP/public/users/companies/companies-login.php");
        exit();
    }

    // Obtener ruta de la imagen
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
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Política de Cookies y Privacidad | AndaFP</title>
    <link rel="stylesheet" href="/andaFP/public/assets/css/dashboard.css" />
    <link rel="stylesheet" href="/andaFP/public/assets/css/cookies-info.css" />
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
    <script src="/andaFP/public/assets/js/sidebar.js" defer></script>
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
                <?php if (isset($studentId)) : ?>
                    <li><a href="/andaFP/public/dashboard/students-dashboard.php">Inicio</a></li>
                    <li><a href="/andaFP/public/dashboard/students/your-offers.php">Tus ofertas</a></li>
                    <li><a href="#">Tus estadísticas</a></li>
                    <li><a href="#">Ayuda</a></li>
                    <li><a href="#">Ajustes</a></li>
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
                    <li><a href="/andaFP/src/backend/logout/students-logout.php" id="logout">Cerrar sesión</a></li>
                <?php else : ?>
                    <li><a href="/andaFP/public/dashboard/companies-dashboard.php">Inicio</a></li>
                    <li><a href="/andaFP/public/dashboard/companies/create-offers.php">Publicar ofertas</a></li>
                    <li><a href="#">Tus estadísticas</a></li>
                    <li><a href="#">Ayuda</a></li>
                    <li><a href="#">Ajustes</a></li>
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
                    <li><a href="/andaFP/src/backend/logout/companies-logout.php" id="logout">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Política de Cookies y Privacidad</h1>

        <p>
            En <strong>AndaFP</strong> utilizamos exclusivamente <strong>cookies técnicas</strong> necesarias para el correcto funcionamiento del sitio web y para mantener la sesión activa de los usuarios registrados.
            Estas cookies no recopilan información personal ni se utilizan con fines publicitarios o de análisis.
        </p>

        <h2>Base legal</h2>
        <p>
            Según el <strong>artículo 22.2 de la Ley 34/2002</strong> de Servicios de la Sociedad de la Información y Comercio Electrónico (LSSI), no es necesario obtener el consentimiento del usuario para la instalación de cookies técnicas,
            ya que son imprescindibles para la prestación de un servicio solicitado expresamente por el usuario.
        </p>
        <p>
            Esta interpretación está respaldada por la <strong>Agencia Española de Protección de Datos</strong> (AEPD) en su guía sobre el uso de cookies, que puedes consultar en el siguiente enlace:
        </p>
        <ul style="list-style: none;">
            <li>
                <a href="https://www.aepd.es/sites/default/files/2020-07/guia-cookies.pdf" target="_blank" rel="noopener noreferrer">
                    Guía sobre el uso de las cookies - AEPD (PDF)
                </a>
            </li>
        </ul>

        <h2>Transparencia y derecho a la información</h2>
        <p>
            En cumplimiento del principio de transparencia recogido en el <strong>Reglamento General de Protección de Datos (RGPD)</strong> y la <strong>Ley Orgánica 3/2018</strong> de Protección de Datos y garantía de los derechos digitales (LOPDGDD),
            informamos a nuestros usuarios de la utilización de estas cookies, aunque no se requiera su consentimiento.
        </p>

        <h2>Detalles técnicos</h2>
        <p>
            Estas cookies se activan automáticamente al acceder al sitio y se eliminan al cerrar el navegador o tras un periodo de inactividad. No compartimos esta información con terceros ni la utilizamos para ningún otro propósito.
        </p>

        <h2>Política de Privacidad</h2>
        <p><strong>AndaFP</strong> es una plataforma para la gestión de prácticas de Formación Profesional, desarrollada y mantenida por Jesús Reyes. En AndaFP, tratamos los datos personales de los usuarios conforme a lo dispuesto en el Reglamento General de Protección de Datos (RGPD) (UE) 2016/679 y la Ley Orgánica 3/2018 (LOPDGDD).</p>

        <h3>1. Bases legales del tratamiento de datos</h3>
        <p>El tratamiento de los datos personales en AndaFP se realiza con base en las siguientes legitimaciones conforme al artículo 6 del RGPD:</p>
        <ul>
            <li><strong>Consentimiento del interesado (art. 6.1.a RGPD):</strong> cuando el usuario decide registrarse voluntariamente en la plataforma y facilita sus datos personales.</li>
            <li><strong>Ejecución de un contrato (art. 6.1.b RGPD):</strong> cuando el tratamiento es necesario para la gestión de las prácticas solicitadas por el usuario (por ejemplo, inscribirse a una oferta).</li>
            <li><strong>Cumplimiento de obligaciones legales (art. 6.1.c RGPD):</strong> en aquellos casos en que debamos conservar datos por requerimientos normativos.</li>
            <li><strong>Interés legítimo del responsable (art. 6.1.f RGPD):</strong> para garantizar la seguridad del sistema, prevenir accesos no autorizados y mejorar la funcionalidad del sitio web.</li>
        </ul>

        <h3>2. ¿Qué datos personales recogemos?</h3>
        <p>Los datos se almacenan exclusivamente en nuestra base de datos (no se usan cookies de sesión). Los datos personales que se recogen en AndaFP dependen del tipo de usuario:</p>
        <ul>
            <li><strong>Estudiantes:</strong> nombre, apellidos, nombre de usuario, email, teléfono, ciudad, provincia, especialidad, contraseña cifrada, CV, centro educativo, foto de perfil.</li>
            <li><strong>Empresas:</strong> CIF, nombre, nombre de usuario, email, teléfono, ciudad, provincia, sector, descripción, contraseña cifrada, logo.</li>
            <li><strong>Ofertas:</strong> datos sobre la empresa, ciudad, provincia, modalidad, fechas, especialidad, horario, descripción.</li>
            <li><strong>Solicitudes:</strong> relación entre estudiantes y ofertas, estado y fecha de aplicación.</li>
        </ul>

        <h3>3. Finalidad del tratamiento</h3>
        <p>Los datos se utilizan exclusivamente para:</p>
        <ul>
            <li>Registrar usuarios.</li>
            <li>Gestionar ofertas de prácticas.</li>
            <li>Mantener la sesión del usuario mientras navega por AndaFP.</li>
        </ul>

        <h3>4. Cesión de datos</h3>
        <p>No se ceden datos a terceros ni se comparten con ningún servicio externo. Los datos permanecen dentro de AndaFP y bajo el control del desarrollador responsable.</p>

        <h3>5. Ubicación de los datos</h3>
        <p>Los datos se almacenan localmente en el servidor de desarrollo y se desplegarán próximamente en un hosting privado (Hostinger).</p>

        <h3>6. Conservación de los datos</h3>
        <p>Los datos se conservan mientras el usuario tenga una cuenta activa. El usuario puede solicitar la eliminación de sus datos en cualquier momento desde su área personal.</p>

        <h3>7. Responsable del tratamiento</h3>
        <p>Jesús Reyes, creador y desarrollador de AndaFP. Para cualquier duda o solicitud sobre tus datos, puedes contactar directamente a través de los canales habilitados en la plataforma.</p>
    </main>

    <footer class="footer">
        <span>Proyecto Fin de Grado realizado por Jesús Reyes Espejo</span>
        <br>
        <span>IES Kursaal, 2025.</span>
    </footer>
</body>

</html>