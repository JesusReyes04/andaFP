<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndaFP</title>
    <link rel="stylesheet" href="/andaFP/public/assets/css/login.css">
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
</head>

<body>
    <main>
        <form action="/andaFP/src/backend/login/validate-login-companies.php" method="post" class="loginForm">
            <h2 id="title">Acceso para empresas</h2>
            <div class="inputField">
                <label for="username">Usuario o correo electrónico</label>
                <input type="text" id="username" name="username" required autocomplete="off">
            </div>
            <div class="inputField">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="off">
            </div>
            <div>
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe">Recordarme</label>
            </div>
            <button type="submit">Iniciar sesión</button>
        </form>

        <div class="extraLinksContainer">
            <div class="extraLinks">
                <p>¿Has olvidado tu contraseña? <a href="#">Recuperarla</a></p>
            </div>        
            <div class="extraLinks">
                <p>¿Aún no tienes cuenta como empresa? <a href="/andaFP/public/users/companies/companies-register.php">Solicitar acceso</a></p>
            </div>
        </div>
        
    </main>

    <footer class="footer">
        <span>Proyecto Fin de Grado realizado por Jesús Reyes Espejo</span>
        <br>
        <span>IES Kursaal, 2025.</span>
    </footer>
</body>

</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    echo "<script>alert(" . json_encode($error) . ");</script>";
    unset($_SESSION['login_error']);
}
?>
