<?php
// Bitácora: registrar evento
function registrarEvento($evento, $usuario, $rol, $detalle = '') {
    $file = __DIR__ . '/Menu/Mocks/bitacora.json';
    $bitacora = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $bitacora[] = [
        'evento' => $evento,
        'usuario' => $usuario,
        'rol' => $rol,
        'detalle' => $detalle,
        'fecha' => date('Y-m-d H:i:s')
    ];
    file_put_contents($file, json_encode($bitacora, JSON_PRETTY_PRINT));
}

// Consultar bitácora
function obtenerBitacora() {
    $file = __DIR__ . '/Menu/Mocks/bitacora.json';
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}
