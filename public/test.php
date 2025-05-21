<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Prueba de error</title>
  <script>
    window.onload = function () {
      <?php if (isset($_SESSION['register_error'])): ?>
        const error = <?php echo json_encode($_SESSION['register_error']); ?>;
        alert(error);
        <?php unset($_SESSION['register_error']); ?>
      <?php endif; ?>
    };
  </script>
</head>
<body>
</body>
</html>
