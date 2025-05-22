<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión | AndaFP</title>
    <link rel="stylesheet" href="/andaFP/public/assets/css/login.css">
</head>

<body>
    <main>
        <form action="/andaFP/src/backend/login/validate-login-students.php" method="post" class="loginForm">
            <h2 id="title">Acceso para estudiantes</h2>
            <div class="inputField">
                <label for="username">Nombre de usuario o correo</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="inputField">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe">Recordarme</label>
            </div>
            <button type="submit">Iniciar sesión</button>
        </form>

        <div class="extraLinksContainer">
            <div class="extraLinks">
                <p>¿Olvidaste tu contraseña? <a href="#">Recuperar contraseña</a></p>
            </div>        
            <div class="extraLinks">
                <p>¿Aún no estás registrado? <a href="/andaFP/public/users/students/students-register.php">Regístrate</a></p>
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