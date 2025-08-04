<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "aavdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Error de conexión"]);
  exit;
}

// Obtener método y datos
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    $productos = [];
    while ($row = $result->fetch_assoc()) {
      $productos[] = $row;
    }
    echo json_encode($productos);
    break;

  case 'POST':
    // Validación básica: evitar códigos repetidos
    $codigo = $data['codigo'];
    $verifica = $conn->prepare("SELECT id FROM productos WHERE codigo = ?");
    $verifica->bind_param("s", $codigo);
    $verifica->execute();
    $verifica->store_result();
    if ($verifica->num_rows > 0) {
      http_response_code(400);
      echo json_encode(["error" => "Ya existe un producto con ese código"]);
      exit;
    }

    $stmt = $conn->prepare("INSERT INTO productos (nombre, codigo, precioCompra, precioVenta, stock, categoria, iva, proveedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddisss",
      $data['nombre'],
      $data['codigo'],
      $data['precioCompra'],
      $data['precioVenta'],
      $data['stock'],
      $data['categoria'],
      $data['iva'],
      $data['proveedor']
    );
    $stmt->execute();
    echo json_encode(["success" => true]);
    break;

  case 'PUT':
    $stmt = $conn->prepare("UPDATE productos SET nombre=?, codigo=?, precioCompra=?, precioVenta=?, stock=?, categoria=?, iva=?, proveedor=? WHERE id=?");
    $stmt->bind_param("ssddisssi",
      $data['nombre'],
      $data['codigo'],
      $data['precioCompra'],
      $data['precioVenta'],
      $data['stock'],
      $data['categoria'],
      $data['iva'],
      $data['proveedor'],
      $data['id']
    );
    $stmt->execute();
    echo json_encode(["success" => true]);
    break;

  case 'DELETE':
    $stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(["success" => true]);
    break;

  default:
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}

$conn->close();
