<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="Menu/styless.css">
</head>
<body>
<?php
// ...existing code...

    </body>
</html>

<?php
session_start();
require_once 'Menu/Mocks/usuarios.json'; // Usaremos el JSON como "base de datos" temporal
function getUsuarios() {
    $file = __DIR__ . '/Menu/Mocks/usuarios.json';
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    return json_decode($json, true) ?: [];
}
function saveUsuarios($usuarios) {
    $file = __DIR__ . '/Menu/Mocks/usuarios.json';
    file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT));
}
// Registro de usuario
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $nombre = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if ($nombre && $usuario && $password && in_array($rol, ['Administrador', 'Cajero', 'Supervisor'])) {
        $usuarios = getUsuarios();
        // Verificar si el usuario ya existe
        foreach ($usuarios as $u) {
            if ($u['usuario'] === $usuario) {
                echo json_encode(['success' => false, 'msg' => 'Usuario ya existe']);
                exit;
            }
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $usuarios[] = [
            'nombre' => $nombre,
            'usuario' => $usuario,
            'password' => $hash,
            'rol' => $rol
        ];
        saveUsuarios($usuarios);
        echo json_encode(['success' => true, 'msg' => 'Usuario registrado']);
        exit;
    } else {
        echo json_encode(['success' => false, 'msg' => 'Datos incompletos']);
        exit;
    }
}
// Login de usuario
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $usuarios = getUsuarios();
    foreach ($usuarios as $u) {
        if ($u['usuario'] === $usuario && password_verify($password, $u['password'])) {
            $_SESSION['usuario'] = $u['usuario'];
            $_SESSION['nombre'] = $u['nombre'];
            $_SESSION['rol'] = $u['rol'];
            // Registrar bitácora de inicio de sesión
            registrarBitacora('login', $u['usuario'], $u['rol']);
            echo json_encode(['success' => true, 'msg' => 'Login exitoso', 'rol' => $u['rol']]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'msg' => 'Credenciales incorrectas']);
    exit;
}
// Función para registrar eventos críticos
function registrarBitacora($evento, $usuario, $rol) {
    $file = __DIR__ . '/Menu/Mocks/bitacora.json';
    $bitacora = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $bitacora[] = [
        'evento' => $evento,
        'usuario' => $usuario,
        'rol' => $rol,
        'fecha' => date('Y-m-d H:i:s')
    ];
    file_put_contents($file, json_encode($bitacora, JSON_PRETTY_PRINT));
}
// Validación de acceso y sesión
function validarAcceso($rolesPermitidos = []) {
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }
    if ($rolesPermitidos && !in_array($_SESSION['rol'], $rolesPermitidos)) {
        echo 'Acceso denegado';
        exit;
    }
}
// Cierre de sesión
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}
