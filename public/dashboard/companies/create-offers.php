<?php
session_start();
require('../../../src/backend/db_conection/conection.php');
$conection = getConnection();

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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>andaFP</title>
    <link rel="stylesheet" href="/andaFP/public/assets/css/create-offers-form.css">
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
                <li><a href="/andaFP/public/dashboard/companies-dashboard.php">Inicio</a></li>
                <li><a href="/andaFP/public/dashboard/companies/create-offers.php">Candidaturas</a></li>
                <li><a href="#">Tus estadísticas</a></li>
                <li><a href="#">Ayuda</a></li>
                <li><a href="#">Ajustes</a></li>
                <li><a href="#">Sobre nosotros</a></li>
                <li><a href="#">Política de datos</a></li>
                <li><a href="/andaFP/src/backend/logout/companies-logout.php" id="logout">Cerrar sesión</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="form-container">
            <form class="offers-form" method="post" id="offers-form" action="/andaFP/src/backend/sections/create-offers.php">
                <h2 id="title">Publica tu oferta aquí</h2>

                <div class="inputField">
                    <label for="offer-title">Título</label>
                    <input type="text" id="offer-title" name="offer-title" required autocomplete="off">
                </div>

                <div class="inputField">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" rows="1" required></textarea>
                </div>

                <div class="inputField">
                    <label for="province">Provincia</label>
                    <div class="place-wrapper">
                        <input type="text" id="province" name="province" autocomplete="off" required
                            style="width: 100%;">
                        <ul id="placeSuggestionsList" class="suggestions-list"></ul>
                    </div>
                </div>

                <div class="inputField">
                    <label for="city">Ciudad</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <div class="inputField">
                    <label for="location">Ubicación</label>
                    <input type="text" id="location" name="location" required autocomplete="off">
                </div>

                <div class="inputField">
                    <label for="startDate">Fecha de inicio</label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>

                <div class="inputField">
                    <label for="schedule">Horario</label>
                    <input type="text" id="schedule" name="schedule" required autocomplete="off">
                </div>

                <div class="inputField">
                    <label for="modality">Modalidad</label>
                    <select id="modality" name="modality" required>
                        <option value="onsite" class="modality-options">Presencial</option>
                        <option value="remote" class="modality-options">Remoto</option>
                        <option value="hybrid" class="modality-options">Híbrido</option>
                    </select>
                </div>
                <div class="buttons-fomr">
                    <button class="deleteInputsValue" id="deleteInputsValue">Limpiar datos</button>
                    <input type="submit" value="Publicar oferta" class="submit-btn">
                </div>
            </form>
        </div>
    </main>

    <footer>

    </footer>

    <script src="/andaFP/public/assets/js/create-offers-form.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceInput = document.getElementById('province');
            provinceInput.addEventListener('input', showPlaceSuggestions);
        });
    </script>
</body>

</html>
<?php
if (isset($_SESSION['register_success'])) {
    echo '<script>document.addEventListener("DOMContentLoaded", function() {
        const successDiv = document.createElement("div");
        successDiv.innerText = "' . addslashes($_SESSION['register_success']) . '";
        successDiv.classList.add("success-message");
        document.body.appendChild(successDiv);
        setTimeout(() => successDiv.remove(), 3000);
    });</script>';
    unset($_SESSION['register_success']);
}
?>
