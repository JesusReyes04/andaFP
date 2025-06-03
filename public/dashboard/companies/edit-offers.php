<?php
session_start();
require('../../../src/backend/db_conection/conection.php');
$conection = getConnection();

$companyId = $_COOKIE['company_id'] ?? null;
$offerId = $_GET['id'] ?? null;

// if theres no company ID or offer ID, redirect to source not found page
if (!$companyId || !$offerId) {
    header("Location: /andaFP/src/frontend/components/source-not-found.html");
    exit();
}

// verify if the offer belongs to the company
$stmt = $conection->prepare("SELECT * FROM offers WHERE id = ?");
$stmt->bind_param("i", $offerId);
$stmt->execute();
$result = $stmt->get_result();
$offerData = $result->fetch_assoc();
$stmt->close();

if (!$offerData || $offerData['company_id'] != $companyId) {
    header("Location: /andaFP/src/frontend/components/source-not-found.html");
    exit();
}

// get the profile picture path for the company
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
    <title>Editar oferta - andaFP</title>
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
        <li><a href="/andaFP/public/dashboard/companies/create-offers.php">Publicar ofertas</a></li>
        
        <li><a href="#">Ayuda</a></li>
        <li><a href="#">Ajustes</a></li>
        <li><a href="#">Sobre nosotros</a></li>
        <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
        <li><a href="/andaFP/src/backend/logout/companies-logout.php" id="logout">Cerrar sesión</a></li>
      </ul>
    </nav>
  </aside>


    <main class="main-content">
        <div class="form-container">
            <form class="offers-form" id="edit-form" method="post" action="/andaFP/src/backend/sections/update-offers.php">
                <h2 id="title">Editar oferta</h2>
                <input type="hidden" name="offer_id" value="<?= htmlspecialchars($offerId) ?>">

                <div class="inputField">
                    <label for="offer-title">Título</label>
                    <input type="text" id="offer-title" name="offer-title" required value="<?= htmlspecialchars($offerData['title']) ?>">
                </div>

                <div class="inputField">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" rows="1" required><?= htmlspecialchars($offerData['description']) ?></textarea>
                </div>

                <div class="inputField">
                    <label for="province">Provincia</label>
                    <div class="place-wrapper">
                        <input value="<?= htmlspecialchars($offerData['province']) ?>" type="text" id="province" name="province" autocomplete="off" required
                            style="width: 100%;">
                        <ul id="placeSuggestionsList" class="suggestions-list"></ul>
                    </div>
                </div>

                <div class="inputField">
                    <label for="specialty">Especialidad requerida</label>
                    <div class="place-wrapper">
                        <input type="text" id="specialty" name="specialty" autocomplete="off" required value="<?= htmlspecialchars($offerData['required_specialty']) ?>">
                        <ul id="suggestionsList" class="suggestions-list"></ul>
                    </div>
                </div>

                <div class="inputField">
                    <label for="city">Ciudad</label>
                    <input type="text" id="city" name="city" required value="<?= htmlspecialchars($offerData['city']) ?>">
                </div>

                <div class="inputField">
                    <label for="location">Ubicación</label>
                    <input type="text" id="location" name="location" required value="<?= htmlspecialchars($offerData['address']) ?>">
                </div>

                <div class="inputField">
                    <label for="startDate">Fecha de inicio</label>
                    <input type="date" id="startDate" name="startDate" required value="<?= htmlspecialchars($offerData['start_date']) ?>">
                </div>

                <div class="inputField">
                    <label for="schedule">Horario</label>
                    <input type="text" id="schedule" name="schedule" required value="<?= htmlspecialchars($offerData['schedule']) ?>">
                </div>

                <div class="inputField">
                    <label for="modality">Modalidad</label>
                    <select id="modality" name="modality" required>
                        <option value="onsite" <?= $offerData['modality'] === 'onsite' ? 'selected' : '' ?>>Presencial</option>
                        <option value="remote" <?= $offerData['modality'] === 'remote' ? 'selected' : '' ?>>Remoto</option>
                        <option value="hybrid" <?= $offerData['modality'] === 'hybrid' ? 'selected' : '' ?>>Híbrido</option>
                    </select>
                </div>

                <div class="buttons-fomr">
                    <a href="/andaFP/public/dashboard/companies-dashboard.php" class="submit-btn">Cancelar</a>
                    <input type="submit" value="Guardar cambios" class="submit-btn">
                </div>
            </form>
        </div>
    </main>
    <script src="/andaFP/public/assets/js/create-offers-form.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceInput = document.getElementById('province');
            const specialtyInput = document.getElementById('specialty');

            provinceInput.addEventListener('input', showPlaceSuggestions);
            specialtyInput.addEventListener('input', showSuggestions);
        });
    </script>
</body>
</html>