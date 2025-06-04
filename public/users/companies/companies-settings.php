<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../../../src/backend/db_conection/conection.php');
$conection = getConnection();

if (!isset($_SESSION['company_id'])) {
    header("Location: /andaFP/public/users/companies/companies-login.php");
    exit();
}

$companyId = $_SESSION['company_id'];

$query = $conection->prepare("SELECT * FROM companies WHERE id = ?");
$query->bind_param("i", $companyId);
$query->execute();

$result = $query->get_result();
$company = $result->fetch_assoc();

$query->close();

if (!$company) {
    die("Empresa no encontrada.");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes - AndaFP</title>
    <link rel="stylesheet" href="/andaFP/public/assets/css/companies-register-style.css">
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
</head>

<body>
<main>
    <form action="/andaFP/src/backend/settings/update-companies.php" method="post" class="loginForm" enctype="multipart/form-data">
        <h2>Ajustes de Empresa</h2>

        <div class="inputField">
            <label for="tax_id">CIF/NIF</label>
            <input type="text" id="tax_id" name="tax_id" value="<?= htmlspecialchars($company['tax_id']) ?>" required>
        </div>

        <div class="inputField">
            <label for="name">Nombre de la empresa</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($company['name']) ?>" required>
        </div>

        <div class="inputField">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($company['username']) ?>" required>
        </div>

        <div class="inputField">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($company['email']) ?>" required>
        </div>

        <div class="inputField">
            <label for="password">Nueva contraseña (opcional)</label>
            <input type="password" id="password" name="password" autocomplete="new-password">
        </div>

        <div class="inputField">
            <label for="password_confirm">Confirmar nueva contraseña</label>
            <input type="password" id="password_confirm" name="password_confirm">
        </div>

        <div class="inputField">
            <label for="phone">Teléfono</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($company['phone']) ?>" required>
        </div>

        <div class="inputField">
            <label for="province">Provincia</label>
            <div class="place-wrapper">
                <input type="text" id="province" name="province" value="<?= htmlspecialchars($company['province']) ?>" autocomplete="off" required style="width: 100%;">
                <ul id="placeSuggestionsList" class="suggestions-list"></ul>
            </div>
        </div>

        <div class="inputField">
            <label for="city">Ciudad</label>
            <input type="text" id="city" name="city" value="<?= htmlspecialchars($company['city']) ?>" required>
        </div>

        <div class="inputField">
            <label for="sector">Sector</label>
            <input type="text" id="sector" name="sector" value="<?= htmlspecialchars($company['sector']) ?>" required>
        </div>

        <div class="inputField">
            <label for="description">Descripción de la empresa</label>
            <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($company['description']) ?></textarea>
        </div>

        <div class="inputField">
            <label for="profile_picture">Logo o imagen de perfil (JPG, JPEG o PNG)</label>
            <input type="file" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png">
        </div>

        <button type="submit">Guardar cambios</button>
    </form>

    <div style="margin-top: 50px;">
        <p style="text-align: center; color: #444;">Tenga en cuenta que solo se actualizan aquellos cambios en los que el usuario haga cambios</p>
    </div>
</main>

<footer class="footer">
    <span>Proyecto Fin de Grado realizado por Jesús Reyes Espejo</span><br>
    <span>IES Kursaal, 2025.</span>
</footer>

<script src="/andaFP/public/assets/js/update-companies.js"></script>
<script>
    const provinceInput = document.getElementById('province');
    provinceInput.addEventListener('input', showPlaceSuggestions);
</script>

<?php if (isset($_SESSION['register_error'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showError(<?= json_encode($_SESSION['register_error']) ?>);
        });
    </script>
    <?php unset($_SESSION['register_error']); ?>
<?php endif; ?>

</body>
</html>