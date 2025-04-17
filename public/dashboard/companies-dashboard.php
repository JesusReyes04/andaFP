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

// Obtener solo el nombre del archivo (por si guardaste la ruta completa)
$imageFileName = basename($profilePicturePath);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <header class="header">
        <div class="logo">AndaFP</div>
        <nav class="nav">
            <a href="#">Mis ofertas</a>
            <a href="login-select.html">Acceder</a>
            <img src="/andaFP/src/frontend/profile-image/<?php echo $imageFileName; ?>" alt="" width="50" height="auto">
        </nav>
    </header>
    <main>

    </main>
    <footer></footer>
</body>

</html>