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
    <link rel="stylesheet" href="/andaFP/public/assets/css/help-menu.css">
    <script src="/andaFP/public/assets/js/sidebar.js" defer></script>
    <title>Ayuda | AndaFP</title>
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
                    <li><a href="/andaFP/src/backend/sections/help.php">Ayuda</a></li>
                    <li><a href="/andaFP/public/users/companies/companies-settings.php">Ajustes</a></li>
                    <li><a href="/andaFP/src/backend/sections/about-us.php">Sobre nosotros</a></li>
                    <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
                    <li><a href="/andaFP/src/backend/logout/companies-logout.php" id="logout">Cerrar sesión</a></li>
                <?php else : ?>
                    <!-- students -->
                    <li><a href="/andaFP/public/dashboard/students-dashboard.php">Inicio</a></li>
                    <li><a href="/andaFP/src/backend/sections/applications-page.php">Candidaturas</a></li>
                    <li><a href="/andaFP/src/backend/sections/help.php">Ayuda</a></li>
                    <li><a href="/andaFP/public/users/students/students-settings.php">Ajustes</a></li>
                    <li><a href="/andaFP/src/backend/sections/about-us.php">Sobre nosotros</a></li>
                    <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
                    <li><a href="/andaFP/src/backend/logout/students-logout.php" id="logout">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <?php if (!isset($studentId)) : ?>
            <h1>Guía de uso para empresas - AndaFP</h1>

            <p>Bienvenido a <strong>AndaFP</strong>, la plataforma que conecta empresas con estudiantes de Formación Profesional en busca de prácticas. Esta guía está diseñada para ayudarte a utilizar la plataforma de forma eficiente como empresa colaboradora.</p>

            <h2>1. Registro de empresa</h2>
            <p>Para comenzar a utilizar AndaFP, la empresa debe registrarse mediante el formulario disponible en la página principal. Los datos solicitados son:</p>
            <ul>
                <li>CIF/NIF de la empresa</li>
                <li>Nombre comercial</li>
                <li>Nombre de usuario (único)</li>
                <li>Email de contacto</li>
                <li>Teléfono</li>
                <li>Ciudad y provincia</li>
                <li>Sector de actividad</li>
                <li>Descripción breve de la empresa</li>
                <li>Contraseña de acceso</li>
                <li>Imagen de perfil</li>
            </ul>
            <blockquote>⚠️ Todos los datos deben ser verídicos. El equipo de AndaFP puede revisar los registros para validar su autenticidad.</blockquote>

            <h2>2. Acceso al panel de empresa</h2>
            <p>Una vez registrada, la empresa puede iniciar sesión con su nombre de usuario y contraseña. Desde el <strong>panel de control</strong>, tendrá acceso a:</p>
            <ul>
                <li>Publicación de nuevas ofertas</li>
                <li>Gestión de ofertas activas o pasadas</li>
                <li>Revisión de solicitudes de estudiantes</li>
                <li>Edición del perfil de empresa</li>
            </ul>

            <h2>3. Publicar una oferta de prácticas</h2>
            <p>Para publicar una oferta, accede a la pestaña <strong>"Publicar Oferta"</strong> y completa el formulario con la siguiente información:</p>
            <ul>
                <li>Título del puesto o actividad</li>
                <li>Descripción de tareas</li>
                <li>Requisitos específicos (si los hay)</li>
                <li>Provincia donde se realizará la práctica</li>
                <li>Duración aproximada</li>
                <li>Fecha de inicio estimada</li>
            </ul>
            <blockquote>✅ Recomendación: redacta las ofertas con claridad, resaltando los beneficios para el estudiante y los aprendizajes que obtendrá.</blockquote>

            <h2>4. Gestión de solicitudes de estudiantes</h2>
            <p>En la sección <strong>"Inicio"</strong>, cada oferta publicada incluye un acceso a la lista de estudiantes que han solicitado realizar prácticas en ella. Desde esta vista podrás:</p>
            <ul>
                <li>Ver el perfil del estudiante</li>
                <li>Descargar su currículum</li>
                <li>Contactar directamente con él/ella</li>
                <li>Marcarlo como “Aceptado” o “Rechazado”</li>
            </ul>
            <blockquote>💡 Utiliza filtros para gestionar mejor las solicitudes cuando el volumen sea alto.</blockquote>

            <h2>5. Edición del perfil de empresa</h2>
            <p>Puedes actualizar los datos de tu empresa desde el apartado <strong>"Ajustes"</strong>, incluyendo:</p>
            <ul>
                <li>Información de contacto</li>
                <li>Imagen de perfil</li>
                <li>Descripción</li>
                <li>Cambiar contraseña</li>
            </ul>
            <blockquote>🔒 Todos los cambios se guardan automáticamente y se aplican al instante.</blockquote>

            <h2>6. Buenas prácticas</h2>
            <ul>
                <li>Publica ofertas actualizadas y bien detalladas.</li>
                <li>Responde a las solicitudes de estudiantes con profesionalismo y en un plazo razonable.</li>
                <li>Informa a AndaFP si una oferta ya ha sido cubierta.</li>
                <li>Valora a los estudiantes tras la finalización de las prácticas (si se habilita esta opción en futuras versiones).</li>
            </ul>

            <h2>7. Soporte</h2>
            <div class="contacto">
                <p>Si necesitas asistencia técnica o tienes dudas sobre el funcionamiento de la plataforma, puedes contactar con el equipo de soporte:</p>
                <p><strong>📧 Email:</strong> soporte@andafp.es</p>
                <p><strong>📞 Teléfono:</strong> 900 000 000 (Horario de atención: 9:00 – 14:00)</p>
            </div>

            <p>Gracias por ser parte de AndaFP.</p>
        <?php else : ?>
            <h1>Guía de uso para estudiantes - AndaFP</h1>
            <p>Bienvenido a <strong>AndaFP</strong>, la plataforma que conecta estudiantes de Formación Profesional con empresas que ofrecen prácticas. Esta guía está diseñada para ayudarte a utilizar la plataforma de forma eficiente como estudiante.</p>
            <h2>1. Registro de estudiante</h2>
            <p>Para comenzar a utilizar AndaFP, debes registrarte mediante el formulario disponible en la página principal. Los datos solicitados son:</p>
            <ul>
                <li>DNI/NIE del estudiante</li>
                <li>Nombre completo</li>
                <li>Nombre de usuario (único)</li>
                <li>Email de contacto</li>
                <li>Teléfono</li>
                <li>Ciudad y provincia</li>
                <li>Ciclo formativo cursado</li>
                <li>Descripción breve de tu perfil profesional</li>
                <li>Contraseña de acceso</li>
                <li>Imagen de perfil</li>
                <li>Currículum vitae</li>
            </ul>
            <blockquote>⚠️ Todos los datos deben ser verídicos. El equipo de AndaFP puede revisar los registros para validar su autenticidad.</blockquote>
            <h2>2. Acceso al panel de estudiante</h2>
            <p>Una vez registrado, puedes iniciar sesión con tu nombre de usuario y contraseña. Desde el <strong>panel de control</strong>, tendrás acceso a:</p>
            <ul>
                <li>Listado de ofertas de prácticas disponibles</li>
                <li>Gestión de tus candidaturas enviadas</li>
                <li>Edición de tu perfil personal</li>
                <li>Acceso a la sección de ayuda y soporte</li>
            </ul>
            <h2>3. Búsqueda y aplicación a ofertas de prácticas</h2>
            <p>En la sección <strong>"Ofertas"</strong>, podrás ver todas las ofertas de prácticas disponibles. Puedes filtrar por:</p>
            <ul>
                <li>Provincia</li>
                <li>Modalidad de prácticas</li>
                <li>Fecha de publicación</li>
                <li>Especialidad del ciclo formativo</li>
            </ul>
            <p>Para postularte a una oferta, haz clic en el botón <strong>"Solicitar"</strong> y completa el formulario con la información requerida. Asegúrate de adjuntar tu currículum actualizado.</p>
            <blockquote>✅ Recomendación: personaliza tu currículum para cada oferta a la que te postules, destacando las habilidades y experiencias más relevantes.</blockquote>
            <h2>4. Gestión de candidaturas</h2>
            <p>En la sección <strong>"Candidaturas"</strong>, podrás ver todas las ofertas a las que te has postulado, junto con su estado actual (pendiente, aceptada, rechazada). Desde aquí puedes:</p>
            <ul>
                <li>Ver detalles de cada oferta</li>
                <li>Cancelar una candidatura si aún no ha sido revisada</li>
                <li>Contactar con la empresa para más información</li>
            </ul>
            <blockquote>💡 Utiliza filtros para gestionar mejor tus candidaturas cuando el volumen sea alto.</blockquote>
            <h2>5. Edición del perfil de estudiante</h2>
            <p>Puedes actualizar tus datos personales desde el apartado <strong>"Ajustes"</strong>, incluyendo:</p>
            <ul>
                <li>Información de contacto</li>
                <li>Imagen de perfil</li>
                <li>Descripción personal</li>
                <li>Cambiar contraseña</li>
            </ul>
            <blockquote>🔒 Todos los cambios se guardan automáticamente y se aplican al instante.</blockquote>
            <h2>6. Buenas prácticas</h2>
            <ul>
                <li>Revisa periódicamente las nuevas ofertas publicadas.</li>
                <li>Personaliza tu currículum para cada candidatura.</li>
                <li>Mantén actualizada tu información de contacto.</li>
                <li>Respeta los tiempos de respuesta de las empresas.</li>
            </ul>
            <h2>7. Soporte</h2>
            <div class="contacto">
                <p>Si necesitas asistencia técnica o tienes dudas sobre el funcionamiento de la plataforma, puedes contactar con el equipo de soporte:</p>
                <p><strong>📧 Email:</strong> soporte@andafp.es</p>
                <p><strong>📞 Teléfono:</strong> 900 000 000 (Horario de atención: 9:00 – 14:00)</p>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>