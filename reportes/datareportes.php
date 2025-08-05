<?php
header('Content-Type: application/json');

// Configuración conexión
$host = "localhost";
$user = "root";
$pass = "";
$db   = "aavdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit();
}

// Función para obtener totales
function obtenerTotal($conn, $where = "") {
    $sql = "SELECT SUM(total_line) as total FROM caja $where";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['total'] ?? 0;
}

// Recibir parámetros de filtro
$filtro = $_GET['filtro'] ?? "";
$valor  = $_GET['valor'] ?? "";

// Construir condición para SQL
$whereFiltro = "";

if ($filtro === "dia" && $valor) {
    $whereFiltro = "WHERE DATE(created_at) = '$valor'";
} elseif ($filtro === "mes" && $valor) {
    $whereFiltro = "WHERE DATE_FORMAT(created_at, '%Y-%m') = '$valor'";
} elseif ($filtro === "anio" && $valor) {
    $whereFiltro = "WHERE YEAR(created_at) = '$valor'";
}

// =========================
// 1. Ventas
// =========================
$totalHoy    = ($whereFiltro) ? obtenerTotal($conn, $whereFiltro) : obtenerTotal($conn, "WHERE DATE(created_at) = CURDATE()");
$totalSemana = ($whereFiltro) ? obtenerTotal($conn, $whereFiltro) : obtenerTotal($conn, "WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)");
$totalMes    = ($whereFiltro) ? obtenerTotal($conn, $whereFiltro) : obtenerTotal($conn, "WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");

// Crecimiento (comparación con semana anterior si no hay filtro)
if (!$whereFiltro) {
    $totalSemanaAnterior = obtenerTotal($conn, "WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) - 1");
    $crecimiento = ($totalSemanaAnterior > 0) ? (($totalSemana - $totalSemanaAnterior) / $totalSemanaAnterior) * 100 : 0;
} else {
    $crecimiento = 0;
}

// =========================
// 2. Detalle de ventas
// =========================
$sqlDetalle = "SELECT created_at as fecha, name as productos, total_line as total, 0 as costo, total_line as ganancia 
               FROM caja 
               " . ($whereFiltro ? $whereFiltro : "") . "
               ORDER BY created_at DESC 
               LIMIT 50";
$resDetalle = $conn->query($sqlDetalle);
$detalleVentas = [];
while ($row = $resDetalle->fetch_assoc()) {
    $row['costo'] = $row['total'] * 0.6; // Ejemplo: costo 60% del total
    $row['ganancia'] = $row['total'] - $row['costo'];
    $detalleVentas[] = $row;
}

// =========================
// 3. Ingresos, ganancias y pérdidas
// =========================
$ingresosTotales = ($whereFiltro) ? obtenerTotal($conn, $whereFiltro) : obtenerTotal($conn);
$gananciasTotales = array_sum(array_column($detalleVentas, 'ganancia'));
$perdidasTotales = 0;

// =========================
// 4. Gráfico circular (productos más vendidos)
// =========================
$sqlProductos = "SELECT name as productos, COUNT(*) as cantidad 
                 FROM caja 
                 " . ($whereFiltro ? $whereFiltro : "") . "
                 GROUP BY name";
$resProductos = $conn->query($sqlProductos);
$productosLabels = [];
$productosData = [];
while ($row = $resProductos->fetch_assoc()) {
    $productosLabels[] = $row['productos'];
    $productosData[] = (int)$row['cantidad'];
}

// =========================
// 5. Gráfico de columnas (ventas por día últimos 7 días)
// =========================
$sqlPorDia = "SELECT DATE(created_at) as fechaDia, SUM(total_line) as totalDia 
              FROM caja 
              " . ($whereFiltro ? $whereFiltro : "") . "
              GROUP BY fechaDia 
              ORDER BY fechaDia DESC 
              LIMIT 7";
$resPorDia = $conn->query($sqlPorDia);
$labelsDias = [];
$dataDias = [];
while ($row = $resPorDia->fetch_assoc()) {
    $labelsDias[] = $row['fechaDia'];
    $dataDias[] = (float)$row['totalDia'];
}
$labelsDias = array_reverse($labelsDias);
$dataDias = array_reverse($dataDias);

// =========================
// Respuesta JSON
// =========================
echo json_encode([
    "ventas" => [
        "hoy" => $totalHoy,
        "semana" => $totalSemana,
        "mes" => $totalMes,
        "crecimiento" => round($crecimiento, 2)
    ],
    "ingresos" => [
        "totales" => $ingresosTotales,
        "ganancias" => $gananciasTotales,
        "perdidas" => $perdidasTotales
    ],
    "detalle" => $detalleVentas,
    "graficoCircular" => [
        "labels" => $productosLabels,
        "data" => $productosData
    ],
    "graficoColumnas" => [
        "labels" => $labelsDias,
        "data" => $dataDias
    ]
], JSON_UNESCAPED_UNICODE);

$conn->close();