<?php
session_start();
require('../../backend/db_conection/conection.php');

$conection = getConnection();

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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de oferta no válido.";
    exit();
}

$offerId = intval($_GET['id']);

$stmt = $conection->prepare("
  SELECT offers.*, companies.name AS company_name, companies.profile_picture AS company_profile_picture
  FROM offers
  INNER JOIN companies ON offers.company_id = companies.id
  WHERE offers.id = ?
");
$stmt->bind_param("i", $offerId);
$stmt->execute();
$result = $stmt->get_result();
$offer = $result->fetch_assoc();
$stmt->close();

if (!$offer) {
    echo "Oferta no encontrada.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndaFP</title>
    <link rel="stylesheet" href="/andaFP/public/assets/css/main-dashboard.css">
    <link rel="stylesheet" href="/andaFP/public/assets/css/view-offer.css">
    <script src="/andaFP/public/assets/js/sidebar.js" defer></script>
    <script src="/andaFP/public/assets/js/view-offer.js"></script>
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
                <li><a href="/andaFP/src/backend/logout/companies-logout.php" id="logout">Cerrar sesión</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <div class="oferta-panel-principal">
            <div class="oferta-header">
                <div class="logo-y-info">
                    <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars(basename($offer['company_profile_picture'])); ?>" alt="Logo empresa" class="company-logo">
                    <div class="info-empresa">
                        <h1 class="titulo-oferta"><?php echo htmlspecialchars($offer['title']); ?></h1>
                        <p class="empresa-nombre"><?php echo htmlspecialchars($offer['company_name']); ?></p>
                    </div>
                </div>
                <button class="apply-btn" data-offer-id="<?= $offerId ?>" id="aplyBtn">Aplicar</button>
            </div>
            <div class="info-basica">
                <div><i class="icon">📍</i> <?php echo htmlspecialchars($offer['city'] . ', ' . $offer['province']); ?></div>
                <div><i class="icon">🏠</i> <?php echo htmlspecialchars($offer['modality']); ?></div>
                <div>
                    <i class="icon">📅</i>
                    <span class="fecha-publicacion" data-created-at="<?php echo nl2br(htmlspecialchars($offer['created_at'])); ?>">
                        Publicada...
                    </span>
                </div>
            </div>
        </div>
        <div class="oferta-panel-descripcion">
            <h2>Descripción de la oferta</h2>
            <p><?php echo nl2br(htmlspecialchars($offer['description'])); ?></p>
        </div>
    </main>
    <script>
        window.onload = function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const closeBtn = document.getElementById('close-btn');

            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });

            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('active');
            });
        };
    </script>
</body>

</html>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fechaElem = document.querySelector('.fecha-publicacion');
        if (!fechaElem) return;

        const createdAtStr = fechaElem.dataset.createdAt;
        const createdAt = new Date(createdAtStr);
        const ahora = new Date();

        const diffMs = ahora - createdAt;
        const diffSeg = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSeg / 60);
        const diffHoras = Math.floor(diffMin / 60);
        const diffDias = Math.floor(diffHoras / 24);

        let texto = '';

        if (diffSeg < 60) {
            texto = `Publicada hace ${diffSeg} segundo${diffSeg === 1 ? '' : 's'}`;
        } else if (diffMin < 60) {
            texto = `Publicada hace ${diffMin} minuto${diffMin === 1 ? '' : 's'}`;
        } else if (diffHoras < 24) {
            texto = `Publicada hace ${diffHoras} hora${diffHoras === 1 ? '' : 's'}`;
        } else if (diffDias === 1) {
            texto = 'Publicada ayer';
        } else if (diffDias <= 2) {
            texto = `Publicada hace ${diffDias} días`;
        } else {
            const dia = createdAt.getDate().toString().padStart(2, '0');
            const mes = (createdAt.getMonth() + 1).toString().padStart(2, '0');
            const año = createdAt.getFullYear().toString().slice(-2);
            texto = `Publicada el ${dia}/${mes}/${año}`;
        }

        fechaElem.textContent = texto;
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
</script>

<?php if (isset($_SESSION['register_error']) && !empty($_SESSION['register_error'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const error = <?= json_encode($_SESSION['register_error']) ?>;
        showError(error);
    });
</script>
<?php unset($_SESSION['register_error']); endif; ?>
