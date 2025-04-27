<?php
session_start();

$_SESSION = [];

session_destroy();

if (isset($_COOKIE['student_id'])) {
    setcookie('student_id', '', time() - 3600, '/'); // La vencemos en el pasado
}

header("Location: /andaFP/public");
exit();
?>