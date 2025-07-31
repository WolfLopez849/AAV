<?php
require 'config.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) exit(json_encode(['ok' => false, 'msg' => 'JSON vacío']));

$pdo->beginTransaction();

try {
  /* 1. Cliente (reutiliza si existe) */
  $cust = $input['customer'];
  $stmt = $pdo->prepare(
    "INSERT INTO customers (name, doc_type, doc_num, email, phone)
     VALUES (?,?,?,?,?)
     ON DUPLICATE KEY UPDATE customer_id = LAST_INSERT_ID(customer_id)"
  );
  $stmt->execute([
    $cust['name'], $cust['doc_type'], $cust['doc_num'],
    $cust['email'], $cust['phone']
  ]);
  $customer_id = $pdo->lastInsertId();

  /* 2. Venta */
  $stmt = $pdo->prepare("INSERT INTO sales (customer_id, total) VALUES (?,?)");
  $stmt->execute([$customer_id, $input['total']]);
  $sale_id = $pdo->lastInsertId();

  /* 3. Detalle */
  $itemSQL  = "INSERT INTO sale_items
               (sale_id, product_id, price_unit, iva_percent, qty, total_line)
               VALUES (?,?,?,?,?,?)";
  $itemStmt = $pdo->prepare($itemSQL);

  foreach ($input['items'] as $it) {
    $itemStmt->execute([
      $sale_id,
      $it['product_id'],
      $it['price_unit'],
      $it['iva_percent'],
      $it['qty'],
      $it['total_line']
    ]);
  }

  $pdo->commit();
  echo json_encode(['ok' => true, 'sale_id' => $sale_id]);

} catch (Exception $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
}
