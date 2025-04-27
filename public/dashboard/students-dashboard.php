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
$query = $conection->prepare("SELECT profile_picture FROM students WHERE id = ?");
$query->bind_param("i", $studentId);
$query->execute();
$query->bind_result($profilePicturePath);
$query->fetch();
$query->close();

// obtener solo el nombre del archivo
$imageFileName = basename($profilePicturePath);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="/andaFP/public/assets/css/students-dashboard.css">
  <script src="/andaFP/public/assets/js/students-dashboard.js" defer></script>
</head>

<body>
  <header class="header">
    <div class="header-container">
      <button id="menu-toggle" class="menu-btn">&#9776;</button>
      <h1 class="andafp">andaFP</h1>
      <img src="/andaFP/src/frontend/profile-image/<?php echo htmlspecialchars($imageFileName);?>" alt="" class="profile-pic">
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

  <main class="main-content">
    <h2>Contenido Principal</h2>
    <p>Este es un ejemplo de contenido con una sidebar a la izquierda y el nombre andaFP a la derecha del header.</p>
  </main>
</body>

</html>