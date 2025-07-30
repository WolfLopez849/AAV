<?php
// Esto va al inicio
$error = "";
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - POSNOVA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="estilo_login.css">
</head>
<body>

  <div class="container">
    <div class="left">
      <form class="formulario" method="POST" action="procesar_login.php">
        <h2>Iniciar sesión en POSNOVA</h2>

        <label>Usuario</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <div class="password-wrapper">
          <input type="password" name="contrasena" id="contrasena" required>
          <img src="img/ver_contraseña.png" alt="Mostrar" width="20" class="toggle-password" onclick="togglePassword()">
        </div>

        <input type="submit" value="Ingresar">

        <?php if (!empty($error)) : ?>
          <p class="mensaje" id="mensaje-error"><?= $error ?></p>
        <?php endif; ?>
      </form>
    </div>

    <div class="right">
      <div class="overlay-text">POSNOVA</div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passInput = document.getElementById("contrasena");
      passInput.type = passInput.type === "password" ? "text" : "password";
    }

    // Ocultar el mensaje automáticamente después de 3 segundos
    const mensaje = document.getElementById("mensaje-error");
    if (mensaje) {
      setTimeout(() => {
        mensaje.style.display = "none";
      }, 3000); // 3000ms = 3 segundos
    }
  </script>

</body>
</html>
