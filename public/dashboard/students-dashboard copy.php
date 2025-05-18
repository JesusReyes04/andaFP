<?php
session_start();
require('../../src/backend/db_conection/conection.php');
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="/andaFP/public/assets/css/rework-dashboard.css">
  <script src="/andaFP/public/assets/js/students-dashboard-rebuild.js" defer></script>
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
        <li><a href="#">Política de datos</a></li>
        <li><a href="/andaFP/src/backend/logout/students-logout.php" id="logout">Cerrar sesión</a></li>
      </ul>
    </nav>
  </aside>

  <div class="form-container">
    <h2 id="welcome-msg">Bienvenido <?php echo $username ?>, aquí comienza tu camino</h2>
    <div class="search-form" id="searchForm">
      <h2>Buscar las mejores ofertas</h2>
      <div class="search-fields">
        <div class="input-group">
          <input type="text" id="searchInput" name="title" placeholder="Título formativo" autocomplete="off">
          <ul id="suggestionsList" class="suggestions-list"></ul>
        </div>
        <div class="input-group">
          <input type="text" id="placeInput" name="province" placeholder="Provincia" autocomplete="off">
          <ul id="placeSuggestionsList" class="suggestions-list"></ul>
        </div>
        <button id="submitForm">Buscar</button>
      </div>
    </div>
  </div>

  <main class="main-content">
    <?php if (!empty($offers)): ?>
      <div class="card-container">
        <?php foreach ($offers as $offer): ?>
          <div class="job-card">
            <div class="job-top">
              <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars(basename($offer['company_profile_picture'])); ?>" class="job-img">
              <div class="job-header">
                <h3 class="job-title"><?php echo htmlspecialchars($offer['title']); ?></h3>
                <p class="job-company"><?php echo htmlspecialchars($offer['company_name']); ?></p>
              </div>
            </div>

            <div class="job-meta">
              <span id="job-ubication"><strong class="job-data">Ubicación:</strong> <?= htmlspecialchars($offer['city']) ?>, <?= htmlspecialchars($offer['province']) ?></span>
              <span id="job-modality"><strong class="job-data">Modalidad:</strong> <?= htmlspecialchars($offer['modality']) ?></span>
            </div>
            <p class="job-description"><?php echo htmlspecialchars($offer['description']); ?></p>
            <div class="job-footer">
              <span class="job-date"><?php echo htmlspecialchars($offer['created_at']); ?></span>
              <div class="job-actions">
                <a href="/andaFP/src/frontend/components/view-offer.php?id=<?php echo $offer['id']; ?>" class="btn">Ver más</a>
                <button class="btn">Aplicar</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
  </main>
</body>

</html>
<script>
  window.onload = function() {
    const searchInput = document.getElementById('searchInput');
    const placeInput = document.getElementById('placeInput');
    const suggestionsList = document.getElementById('suggestionsList');
    const placeSuggestionsList = document.getElementById('placeSuggestionsList');
    const form = document.getElementById("searchForm");

    searchInput.addEventListener('input', showSuggestions);
    placeInput.addEventListener('input', showPlaceSuggestions);

    searchInput.addEventListener('focus', () => {
      placeSuggestionsList.style.display = 'none';
      suggestionsList.style.display = 'block';
    });

    placeInput.addEventListener('focus', () => {
      suggestionsList.style.display = 'none';
      placeSuggestionsList.style.display = 'block';
    });

    form.addEventListener('click', function(event) {
      validateInputsValues(event, placeInput, searchInput);
    });
  };
</script>