<?php
$host = 'localhost';
$db   = 'aavdb';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Error de conexión: '.$e->getMessage());
}

function getCajaEstado() {
    global $pdo;
    $stmt = $pdo->query("SELECT estado FROM caja_estado WHERE id=1 LIMIT 1");
    $row = $stmt->fetch();
    return $row ? $row['estado'] : 'cerrada';
}

function setCajaEstado($nuevoEstado) {
    global $pdo;
    $estado = ($nuevoEstado === 'abierta') ? 'abierta' : 'cerrada';
    $stmt = $pdo->prepare("UPDATE caja_estado SET estado=? WHERE id=1");
    $stmt->execute([$estado]);
}