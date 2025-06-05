<?php
require('../src/backend/db_conection/conection.php');
$conection = getConnection();

// get params form post
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
} else {
  // get params form GET
  $title = $_GET['title'] ?? '';
  $province = $_GET['place'] ?? '';
  $city = $_GET['city'] ?? '';
  $modality = $_GET['modality'] ?? '';
  $active = "open";


  $offers = [];

  // construir consulta base
  $query = "SELECT offers.*, 
            companies.profile_picture AS company_profile_picture, 
            companies.name AS company_name 
            FROM offers 
            INNER JOIN companies ON offers.company_id = companies.id 
            WHERE 1=1";

  $params = [];
  $types = "";

  // añadir condiciones si existen
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
  <link rel="stylesheet" href="/andaFP/public/assets/css/rework-dashboard.css">
  <script src="/andaFP/public/assets/js/results.js" defer></script>
  <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
  <div class="form-container">
    <h1 style="width: 100%; text-align: center;">AndaFP</h1>
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

  <main class="main-content">
    <h2 style="margin-bottom: 20px;">Los resultados de su búsqueda:</h2>
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
              <span id="job-modality">
                <?php
                switch (htmlspecialchars($offer['modality'])) {
                  case 'onsite':
                    echo '<strong class="job-data">Modalidad:</strong> Presencial';
                    break;
                  case 'remote':
                    echo '<strong class="job-data">Modalidad:</strong> Remoto';
                    break;
                  case 'hybrid':
                    echo '<strong class="job-data">Modalidad:</strong> Híbrido';
                    break;
                  default:
                    echo '<strong class="job-data">Modalidad:</strong> Desconocida';
                }
                ?>
            </div>
            <p class="job-description"><?php echo htmlspecialchars($offer['description']); ?></p>
            <div class="job-footer">
              <span class="job-date" id="date" data-created-at="<?php echo nl2br(htmlspecialchars($offer['created_at'])); ?>">Publicada...</span>
              <div class="job-actions">
                <a href="/andaFP/src/frontend/components/view-offer.php?id=<?php echo $offer['id']; ?>" class="btn" target="_blank">Ver más</a>
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

    const toggle = document.getElementById('toggleAdvanced');
    const advancedFields = document.querySelector('.advanced-fields');
    toggle.addEventListener('click', () => {
      advancedFields.classList.toggle('hidden');
      toggle.textContent = advancedFields.classList.contains('hidden') ? 'Búsqueda avanzada' : 'Ocultar búsqueda avanzada';
    });

    const fechaElems = document.querySelectorAll(".job-date");
    fechaElems.forEach(fechaElem => {
      const createdAtStr = fechaElem.dataset.createdAt;
      const createdAt = new Date(createdAtStr);
      const ahora = new Date();

      const diffMs = ahora - createdAt;
      const diffSeg = Math.floor(diffMs / 1000);
      const diffMin = Math.floor(diffSeg / 60);
      const diffHoras = Math.floor(diffMin / 60);
      const diffDias = Math.floor(diffHoras / 24);

      let texto = "";

      if (diffSeg < 60) {
        texto = `Publicada hace ${diffSeg} segundo${diffSeg === 1 ? "" : "s"}`;
      } else if (diffMin < 60) {
        texto = `Publicada hace ${diffMin} minuto${diffMin === 1 ? "" : "s"}`;
      } else if (diffHoras < 24) {
        texto = `Publicada hace ${diffHoras} hora${diffHoras === 1 ? "" : "s"}`;
      } else if (diffDias === 1) {
        texto = "Publicada ayer";
      } else if (diffDias <= 2) {
        texto = `Publicada hace ${diffDias} días`;
      } else {
        const dia = createdAt.getDate().toString().padStart(2, "0");
        const mes = (createdAt.getMonth() + 1).toString().padStart(2, "0");
        const año = createdAt.getFullYear().toString().slice(-2);
        texto = `Publicada el ${dia}/${mes}/${año}`;
      }

      fechaElem.textContent = texto;
    });

  };
</script>