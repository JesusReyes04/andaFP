Cómo podríamos una confirmación de succes?
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

$offers = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST['title'] ?? '';
  $province = $_POST['province'] ?? '';
  $city = $_POST['city'] ?? '';
  $modality = $_POST['modality'] ?? '';
  $active = "open";

  $query = "SELECT offers.*, 
            companies.profile_picture AS company_profile_picture, 
            companies.name AS company_name 
            FROM offers 
            INNER JOIN companies ON offers.company_id = companies.id 
            WHERE 1=1";

  $params = [];
  $types = "";

  if (!empty($title)) {
    $query .= " AND offers.required_specialty LIKE ?";
    $params[] = "%$title%";
    $types .= "s";
  }

  if (!empty($province)) {
    $query .= " AND offers.province LIKE ?";
    $params[] = "%$province%";
    $types .= "s";
  }

  if (!empty($city)) {
    $query .= " AND offers.city LIKE ?";
    $params[] = "%$city%";
    $types .= "s";
  }

  if (!empty($modality)) {
    $query .= " AND offers.modality = ?";
    $params[] = $modality;
    $types .= "s";
  }

  $query .= " AND offers.status = ?";
  $params[] = $active;
  $types .= "s";


  $query .= " ORDER BY offers.created_at DESC";

  $stmt = $conection->prepare($query);

  if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $offers = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AndaFP</title>
  <script src="/andaFP/public/assets/js/students-dashboard.js" defer></script>
  <link rel="stylesheet" href="/andaFP/public/assets/css/students-dashboard.css">
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
    <div class="form-container">
      <h2 id="welcome-msg">Bienvenido <?php echo $username ?>, aquí comienza tu camino</h2>
      <form class="search-form" action="" method="post" id="searchForm">
        <h2>Busque aquí sus ofertas</h2>
        <div class="search-fields">
          <div class="input-group">
            <input type="text" id="searchInput" name="title" placeholder="Título formativo" autocomplete="off">
            <ul id="suggestionsList" class="suggestions-list"></ul>
          </div>
          <div class="input-group">
            <input type="text" id="placeInput" name="province" placeholder="Provincia" autocomplete="off">
            <ul id="placeSuggestionsList" class="suggestions-list"></ul>
          </div>
        </div>

        <div class="advanced-fields hidden">
          <div class="input-group">
            <input type="text" id="city" name="city" placeholder="Ciudad">
          </div>
          <div class="input-group">
            <select id="modality" name="modality">
              <option value="" disabled selected>Modalidad</option>
              <option value="onsite">Presencial</option>
              <option value="remote">Remoto</option>
              <option value="hybrid">Híbrido</option>
            </select>
          </div>
        </div>

        <div class="buttons-group">
          <button type="button" id="toggleAdvanced">Búsqueda avanzada</button>
          <button type="submit" id="submitForm">Buscar</button>
        </div>
      </form>
    </div>
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
                <a href="/andaFP/src/frontend/components/view-offer.php?id=<?php echo $offer['id']; ?>" class="btn" target="_blank">Ver más</a>
                <button class="apply-btn" data-offer-id="<?= $offer['id'] ?>" id="aplyBtn">Aplicar</button>
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

    form.addEventListener('submit', function(event) {
      validateInputsValues(event, placeInput, searchInput);
    });

    const toggle = document.getElementById('toggleAdvanced');
    const advancedFields = document.querySelector('.advanced-fields');

    toggle.addEventListener('click', () => {
      advancedFields.classList.toggle('hidden');
    });

    document.querySelectorAll('.apply-btn').forEach(button => {
      button.addEventListener('click', () => {
        const offerId = button.getAttribute('data-offer-id');

        fetch('/andaFP/src/backend/apply-offer.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'offer_id=' + encodeURIComponent(offerId)
          })
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              showError(data.error);
            } else {
              showError("¡Postulación exitosa!");
            }
          })
          .catch(error => {
            showError("Error de red. Inténtalo de nuevo.");
          });
      });
    });
  };
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      
    <?php if (isset($_SESSION['register_success'])): ?>
      const success = <?php echo json_encode($_SESSION['register_success']); ?>;
      showError(success);
      <?php unset($_SESSION['register_error']); ?>
    <?php endif; ?>

    // Mostrar mensaje de error si existe 
    <?php if (isset($_SESSION['register_error']) && !empty($_SESSION['register_error'])): ?>
      const error = <?php echo json_encode($_SESSION['register_error']); ?>;
      showError(error);
    <?php unset($_SESSION['register_error']);
    endif; ?>
  });
</script>