<?php
$host = 'localhost';
$db   = 'aavdb';
$user = 'root';      // o el usuario que uses
$pass = '';          // tu contraseña (en XAMPP/Wamp suele ser vacía)
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
