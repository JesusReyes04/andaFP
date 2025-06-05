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
        a.student_id,
        a.status as estao,
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

unset($_SESSION['register_success']);

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
      <h1 class="andafp">AndaFP</h1>
      <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars($imageFileName); ?>" alt="" class="profile-pic">
    </div>
  </header>

  <aside class="sidebar" id="sidebar">
    <button class="close-btn" id="close-btn">&times;</button>
    <nav>
      <ul>
        <li><a href="/andaFP/public/dashboard/companies-dashboard.php">Inicio</a></li>
        <li><a href="/andaFP/public/dashboard/companies/create-offers.php">Publicar ofertas</a></li>
        <li><a href="/andaFP/src/backend/sections/help.php">Ayuda</a></li>
        <li><a href="/andaFP/public/users/companies/companies-settings.php">Ajustes</a></li>
        <li><a href="/andaFP/src/backend/sections/about-us.php">Sobre nosotros</a></li>
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
              <span>
                <?php
                switch (htmlspecialchars($item['modality'])) {
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
            <p class="job-description"><?= htmlspecialchars($item['description']) ?></p>

            <div class="job-footer">
              <span class="job-date" data-created-at="<?php echo nl2br(htmlspecialchars($item['created_at'])); ?>">Publicada...</span>
              <div class="job-actions">
                <a href="/andaFP/public/dashboard/companies/edit-offers.php?id=<?= $item['id'] ?>" class="btn">Editar</a>
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
                    <a href="/andaFP/src/frontend/cv/<?= rawurlencode(basename($applicant['cv'])) ?>" target="_blank" style="text-decoration: none; color: #006331;">Ver CV</a><br><br>
                    <button class="btn-accept" data-student-id="<?= $applicant['student_id'] ?>" data-offer-id="<?= $item['id'] ?>">Aceptar</button>
                    <button class="btn-reject" data-student-id="<?= $applicant['student_id'] ?>" data-offer-id="<?= $item['id'] ?>">Rechazar</button>
                    <span class="status-message" id="status-<?= $item['id'] ?>-<?= $applicant['student_id'] ?>"></span> <br>

                    <?php
                    $estao = $applicant['estao'] ?? 'pending';

                    switch ($estao) {
                      case 'approved':
                        $statusText = 'Aprobado';
                        break;
                      case 'rejected':
                        $statusText = 'Rechazado';
                        break;
                      case 'pending':
                      default:
                        $statusText = 'Pendiente';
                        break;
                    }
                    ?>
                    <small>Estado: <?= $statusText ?></small><br>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div width="100%">
        <h3 style="border-radius: 3px; padding: .5rem; border-left: 6px solid #006331; background: #ffffff; color: #333333; font-size: 1.5rem;">Hoy es un gran día para publicar tu primera oferta</h3>
        <p style="border-radius: 3px; padding: .5rem; color: #333333; margin-bottom: 20px; font-size: 1.2rem; font-weight: normal;">Encuentra a personal formado para las necesidades de tu negocio en AndaFP.</p>
      </div>

    <?php endif; ?>
  </main>

</body>

</html>

<script>
  window.onload = function() {
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