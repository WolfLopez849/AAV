<?php
// Permisos y menú por rol
function getMenuPorRol($rol) {
    $menu = [
        'Administrador' => [
            'Usuarios', 'Productos', 'Facturas', 'Egresos', 'Arqueos', 'Cerrar Sesión'
        ],
        'Cajero' => [
            'Productos', 'Facturas', 'Egresos', 'Cerrar Sesión'
        ],
        'Supervisor' => [
            'Usuarios', 'Productos', 'Facturas', 'Arqueos', 'Cerrar Sesión'
        ]
    ];
    return $menu[$rol] ?? [];
}

// Validar acción permitida
function validarPermiso($accion) {
    session_start();
    $rol = $_SESSION['rol'] ?? '';
    $acciones = [
        'Administrador' => ['usuarios', 'productos', 'facturas', 'egresos', 'arqueos'],
        'Cajero' => ['productos', 'facturas', 'egresos'],
        'Supervisor' => ['usuarios', 'productos', 'facturas', 'arqueos']
    ];
    if (!in_array($accion, $acciones[$rol] ?? [])) {
        echo json_encode(['success' => false, 'msg' => 'Acción no permitida']);
        exit;
    }
}
