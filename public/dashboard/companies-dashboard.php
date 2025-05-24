<?php
session_start();
require('../../src/backend/db_conection/conection.php');
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

$query = $conection->prepare("
    SELECT 
        o.*, 
        s.first_name,
        s.last_name,
        s.email,
        s.province,
        s.specialty,
        s.cv
    FROM 
        offers o
    LEFT JOIN 
        applications a ON o.id = a.offer_id
    LEFT JOIN 
        students s ON a.student_id = s.id
    WHERE 
        o.company_id = ?
    ORDER BY 
        o.id DESC, s.last_name 
");

$query->bind_param("i", $companyId);
$query->execute();

$result = $query->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

$query->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AndaFP</title>
  <link rel="stylesheet" href="/andaFP/public/assets/css/companies-dashboard.css">
  <script src="/andaFP/public/assets/js/companies-dashboard.js" defer></script>
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
        <li><a href="#">Tus estadísticas</a></li>
        <li><a href="#">Ayuda</a></li>
        <li><a href="#">Ajustes</a></li>
        <li><a href="#">Sobre nosotros</a></li>
        <li><a href="/andaFP/src/backend/sections/cookies-info.php">Política de datos</a></li>
        <li><a href="/andaFP/src/backend/logout/companies-logout.php" id="logout">Cerrar sesión</a></li>
      </ul>
    </nav>
  </aside>

  <main class="main-content">
    <?php if (!empty($data)): ?>
      <h2>Ofertas publicadas</h2>

      <div class="card-container">
        <?php
        $groupedOffers = [];
        foreach ($data as $item) {
          $offerId = $item['id'];
          if (!isset($groupedOffers[$offerId])) {
            $groupedOffers[$offerId] = [
              'offer' => $item,
              'applicants' => []
            ];
          }
          if ($item['first_name']) {
            $groupedOffers[$offerId]['applicants'][] = $item;
          }
        }

        foreach ($groupedOffers as $offerId => $group):
          $item = $group['offer'];
        ?>
          <div class="job-card">
            <div class="job-top">
              <div class="job-header">
                <h3 class="job-title"><?= htmlspecialchars($item['title']) ?></h3>
                <p class="job-company"><?= htmlspecialchars($item['company_name'] ?? '') ?></p>
              </div>
            </div>

            <div class="job-meta">
              <span><strong class="job-data">Ubicación:</strong> <?= htmlspecialchars($item['city']) ?>, <?= htmlspecialchars($item['province']) ?></span>
              <span><strong class="job-data">Modalidad:</strong> <?= htmlspecialchars($item['modality']) ?></span>
            </div>
            <p class="job-description"><?= htmlspecialchars($item['description']) ?></p>

            <div class="job-footer">
              <span class="job-date"><?= htmlspecialchars($item['created_at']) ?></span>
              <div class="job-actions">
                <a href="/andaFP/public/dashboard/companies/edit-offer.php?id=<?= $item['id'] ?>" class="btn">Editar</a>
                <button class="toggle-applicants-btn" data-offer-id="<?= $item['id'] ?>">Ver aplicantes</button>
              </div>
            </div>

            <div class="applicants-list" id="applicants-<?= $item['id'] ?>" style="display: none;">
              <h4>Estudiantes que aplicaron:</h4>
              <ul>
                <?php foreach ($group['applicants'] as $applicant): ?>
                  <li>
                    <strong><?= htmlspecialchars($applicant['first_name']) ?> <?= htmlspecialchars($applicant['last_name']) ?></strong><br>
                    <small>Email: <?= htmlspecialchars($applicant['email']) ?></small><br>
                    <small>Provincia: <?= htmlspecialchars($applicant['province']) ?></small><br>
                    <small>Especialidad: <?= htmlspecialchars($applicant['specialty']) ?></small><br>
                    <a href="/andaFP/src/frontend/cv/<?= rawurlencode(basename($applicant['cv'])) ?>" target="_blank" style="text-decoration: none; color: #006331;">Ver CV</a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No se han publicado ofertas todavía.</p>
    <?php endif; ?>
  </main>

</body>

</html>