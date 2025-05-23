<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndaFP</title>
    <link rel="stylesheet" href="/andaFP/public/assets/css/companies-register-style.css">
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
</head>

<body>
    <main>
        <form action="/andaFP/src/backend/register/register-companies.php" method="post" class="loginForm" enctype="multipart/form-data">
            <h2>Registro para Empresas</h2>

            <div class="inputField">
                <label for="tax_id">CIF/NIF</label>
                <input type="text" id="tax_id" name="tax_id" required>
            </div>

            <div class="inputField">
                <label for="name">Nombre de la empresa</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="inputField">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="inputField">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="inputField">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" autocomplete="new-password" required>
            </div>

            <div class="inputField">
                <label for="password_confirm">Confirmar contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>

            <div class="inputField">
                <label for="phone">Teléfono</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="inputField">
                <label for="province">Provincia</label>
                <div class="place-wrapper">
                    <input type="text" id="province" name="province" autocomplete="off" required style="width: 100%;">
                    <ul id="placeSuggestionsList" class="suggestions-list"></ul>
                </div>
            </div>

            <div class="inputField">
                <label for="city">Ciudad</label>
                <input type="text" id="city" name="city" required>
            </div>

            <div class="inputField">
                <label for="sector">Sector</label>
                <input type="text" id="sector" name="sector" required>
            </div>

            <div class="inputField">
                <label for="description">Descripción de la empresa</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="inputField">
                <label for="profile_picture">Logo o imagen de perfil (JPG, JPEG o PNG)</label>
                <input type="file" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png" required>
            </div>

            <button type="submit">Registrarse</button>
        </form>

        <div class="extraLinksContainer">
            <div class="extraLinks">
                <p>¿Ya tienes una cuenta? <a href="/andaFP/public/users/companies/companies-login.php">Inicia sesión</a></p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <span>Proyecto Fin de Grado realizado por Jesús Reyes Espejo</span><br>
        <span>IES Kursaal, 2025.</span>
    </footer>

    <script src="/andaFP/public/assets/js/sanitize-form-inputs.js"></script>
    <script src="/andaFP/public/assets/js/validate-register-comapnies.js"></script>
    <script>
        const provinceInput = document.getElementById('province');
        provinceInput.addEventListener('input', showPlaceSuggestions);
    </script>
    <?php if (isset($_SESSION['register_error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showError(<?= json_encode($_SESSION['register_error']) ?>);
                const provinceInput = document.getElementById('province');
                provinceInput.addEventListener('input', showPlaceSuggestions);
            });
        </script>
        <?php unset($_SESSION['register_error']); ?>
    <?php endif; ?>

</body>
</html>
