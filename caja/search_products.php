<?php
/** =========================================================================
 *  DEVUELVE LA LISTA DE PRODUCTOS EN FORMATO JSON
 *  Espera que config.php cree la variable $pdo  (PDO mysql, utf8mb4)
 *  Campos que necesita main.js:  codigo / nombre / categoria / precio_compra
 *  ========================================================================= */
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';   // ← asegúrate de que la ruta es correcta

try {
    // 1️⃣  Consulta — ajusta el nombre de la tabla si es distinto
    $sql  = "SELECT id, codigo, nombre, categoria, precioVenta AS precio_compra, iva
             FROM   productos
             LIMIT  500";                  
    $stmt = $pdo->query($sql);

    // 2️⃣  Resultado en array asociativo
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* 3️⃣  Asegura que precio_compra sea numérico */
    foreach ($rows as &$r) {
    $r['precio_compra'] = (float) $r['precio_compra'];
    $r['iva'] = (int) $r['iva'];
}

    echo json_encode($rows);            // ← EXACTAMENTE lo que espera DataTables
}
catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error'  => 'Error al consultar productos',
        'detail' => $e->getMessage()
    ]);
}
