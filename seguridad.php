<?php
// Seguridad y gestión de sesión
session_start();
function iniciarSesionSegura($usuario, $nombre, $rol) {
    session_regenerate_id(true);
    $_SESSION['usuario'] = $usuario;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['rol'] = $rol;
}

function cerrarSesionSegura() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function sesionActiva() {
    return isset($_SESSION['usuario']);
}
