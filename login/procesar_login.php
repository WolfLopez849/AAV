<?php
require '../clientes/conexion.php';
$conexion = obtenerConexion();
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];

    if (empty($usuario) || empty($contrasena)) {
        header("Location: login.php?error=Todos los campos son obligatorios.");
        exit;
    }

    try {
        // Buscar usuario activo
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? AND estado = 'activo'");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($contrasena, $user['contrasena'])) {
            // Autenticación correcta
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol'];

            // Registrar acceso
            $log = $conexion->prepare("INSERT INTO accesos (usuario) VALUES (?)");
            $log->execute([$usuario]);

            header("Location: ../Menu/index.php");
            exit;
        } else {
            header("Location: login.php?error=Usuario o contraseña incorrectos.");
            exit;
        }

    } catch (PDOException $e) {
        header("Location: login.php?error=Error en el servidor.");
        exit;
    }
}
?>
