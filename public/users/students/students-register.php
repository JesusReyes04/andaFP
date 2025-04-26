<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['register_error']) && !empty($_SESSION['register_error'])) {
    $error = json_encode($_SESSION['register_error']);
    $showErrorScript = "<script>document.addEventListener('DOMContentLoaded', () => { showError($error); });</script>";
    unset($_SESSION['register_error']);
} else {
    $showErrorScript = '';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndaFP</title>
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
    <link rel="stylesheet" href="/andaFP/public/assets/css/students-register-style.css">
    <script src="/andaFP/public/assets/js/sanitize-form-inputs.js"></script>
    <script src="/andaFP/public/assets/js/validate-register-students.js" defer></script>
</head>

<body>
    <main>
        <form action="/andaFP/src/backend/register/register-students.php" method="post" class="loginForm"
            enctype="multipart/form-data" id="registerForm">
            <h2>Registro para Estudiantes</h2>

            <div class="inputField">
                <label for="first_name">Nombre</label>
                <input type="text" id="first_name" name="first_name" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="last_name">Apellidos</label>
                <input type="text" id="last_name" name="last_name" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="phone">Teléfono</label>
                <input type="tel" id="phone" name="phone" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="province">Provincia</label>
                <div class="place-wrapper">
                    <input type="text" id="province" name="province" autocomplete="off" required>
                    <ul id="placeSuggestionsList" class="suggestions-list"></ul>
                </div>
            </div>

            <div class="inputField">
                <label for="city">Ciudad</label>
                <input type="text" id="city" name="city" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="specialty">Nombre del ciclo formativo</label>
                <div class="place-wrapper">
                    <input type="text" id="specialty" name="specialty" autocomplete="off" required>
                    <ul id="suggestionsList" class="suggestions-list"></ul>
                </div>
            </div>

            <div class="inputField">
                <label for="educational_center">Centro educativo</label>
                <input type="text" id="educational_center" name="educational_center" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="off">
            </div>

            <div class="inputField">
                <label for="password_confirm">Confirmar contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" required> <button>o</button>
            </div>

            <div class="inputField">
                <label for="cv">Currículum (PDF)</label>
                <input type="file" id="cv" name="cv" accept=".pdf" required>
            </div>

            <div class="inputField">
                <label for="profile_picture">Foto de perfil (JPG, JPEG o PNG)</label>
                <input type="file" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png" required>
            </div>

            <input type="submit" value="Registrarse" class="submitButton" id="submitButton">
        </form>

        <div class="extraLinksContainer">
            <div class="extraLinks">
                <p>¿Ya tienes una cuenta? <a href="/andaFP/public/users/students/students-login.php">Inicia sesión</a>
                </p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <span>Proyecto Fin de Grado realizado por Jesús Reyes Espejo</span><br>
        <span>IES Kursaal, 2025.</span>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const specialtyInput = document.getElementById('specialty');
            const provinceInput = document.getElementById('province');

            specialtyInput.addEventListener('input', showSuggestions);
            provinceInput.addEventListener('input', showPlaceSuggestions);
        });
    </script>
    <?php echo $showErrorScript; ?>
</body>

</html>