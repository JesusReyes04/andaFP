<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../../../src/backend/db_conection/conection.php');
$conection = getConnection();

if (!isset($_SESSION['student_id'])) {
    header("Location: /andaFP/public/users/students/students-login.php");
    exit();
}

$studentId = $_SESSION['student_id'];

$query = $conection->prepare("SELECT * FROM students WHERE id = ?");
$query->bind_param("i", $studentId);
$query->execute();
$result = $query->get_result();
$student = $result->fetch_assoc();
$query->close();

if (!$student) {
    die("Estudiante no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ajustes de Estudiante | AndaFP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/andaFP/public/assets/css/students-register-style.css">
    <link rel="shortcut icon" href="/andaFP/public/assets/favicon/andaFP.ico" type="image/x-icon">
</head>

<body>

    <main>
        <form action="/andaFP/src/backend/settings/update-students.php" method="post" class="loginForm" enctype="multipart/form-data">
            <h2>Ajustes del Estudiante</h2>

            <div class="inputField">
                <label for="first_name">Nombre</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
            </div>

            <div class="inputField">
                <label for="last_name">Apellidos</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
            </div>

            <div class="inputField">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($student['username']) ?>" required>
            </div>

            <div class="inputField">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>

            <div class="inputField">
                <label for="phone">Teléfono</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" required>
            </div>

            <div class="inputField">
                <label for="province">Provincia</label>
                <div class="place-wrapper">
                    <input type="text" id="province" name="province" value="<?= htmlspecialchars($student['province']) ?>" autocomplete="off" required>
                    <ul id="placeSuggestionsList" class="suggestions-list"></ul>
                </div>
            </div>

            <div class="inputField">
                <label for="city">Ciudad</label>
                <input type="text" id="city" name="city" value="<?= htmlspecialchars($student['city']) ?>" required>
            </div>

            <div class="inputField">
                <label for="specialty">Nombre del ciclo formativo</label>
                <div class="place-wrapper">
                    <input type="text" id="specialty" name="specialty" value="<?= htmlspecialchars($student['specialty']) ?>" autocomplete="off" required>
                    <ul id="suggestionsList" class="suggestions-list"></ul>
                </div>
            </div>

            <div class="inputField">
                <label for="educational_center">Centro educativo</label>
                <input type="text" id="educational_center" name="educational_center" value="<?= htmlspecialchars($student['educational_center']) ?>" required>
            </div>

            <div class="inputField">
                <label for="password">Nueva contraseña (opcional)</label>
                <input type="password" id="password" name="password" autocomplete="new-password">
            </div>

            <div class="inputField">
                <label for="password_confirm">Confirmar nueva contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
            </div>

            <div class="inputField">
                <label for="cv">Nuevo currículum (PDF)</label>
                <input type="file" id="cv" name="cv" accept=".pdf">
            </div>

            <div class="inputField">
                <label for="profile_picture">Nueva foto de perfil (JPG, JPEG o PNG)</label>
                <input type="file" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png">
            </div>

            <button type="submit" class="submitButton" id="submitButton" style="background-color: #006331;color: white; border: none; padding: 0.85rem 1.5rem; font-size: 1rem; font-weight: 600; border-radius: 0.5rem; cursor: pointer; transition: background-color 0.3s, transform 0.2s; margin-top: 1.2rem; width: 100%;">Guardar cambios</button>
        </form>

        <div style="margin-top: 50px;">
            <p style="text-align: center; color: #444;">Solo se actualizarán los campos modificados</p>
        </div>
    </main>

    <footer class="footer">
        <span>Proyecto Fin de Grado realizado por Jesús Reyes Espejo</span><br>
        <span>IES Kursaal, 2025.</span>
    </footer>

    <script src="/andaFP/public/assets/js/update-student.js"></script>
    <script>
        document.getElementById('province').addEventListener('input', showPlaceSuggestions);
        document.getElementById('specialty').addEventListener('input', showSuggestions);
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