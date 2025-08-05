<?php
require 'config.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) exit(json_encode(['ok' => false, 'msg' => 'JSON vacío']));

$pdo->beginTransaction();

try {
   // Obtén los datos del cliente una sola vez
  $customer = $input['customer'] ?? [];
  $name_client = $customer['name'] ?? '';
  $document = $customer['doc_num'] ?? '';

  foreach ($input['items'] as $it) {
    $stmt = $pdo->prepare(
      "INSERT INTO caja
        (name_client, document, product_id, price_unit, iva_percent, qty, total_line, code, name, category)
       VALUES (?,?,?,?,?,?,?,?,?,?)"
    );
    $stmt->execute([
      $name_client,        // name_client
      $document,           // document
      $it['product_id'],    // product_id
      $it['price_unit'],    // price_unit
      $it['iva_percent'],   // iva_percent
      $it['qty'],           // qty
      $it['total_line'],    // total_line
      $it['code'] ?? '',    // code
      $it['name'] ?? '',    // name
      $it['category'] ?? '' // category
    ]);
  }

  $pdo->commit();
  echo json_encode(['ok' => true]);
} catch (Exception $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
}
