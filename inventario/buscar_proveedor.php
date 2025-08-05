<?php
header('Content-Type: application/json');
require_once '../Config/conectar.php';

class ProveedorDB extends Conectar {
    public function obtenerProveedores() {
        $stmt = $this->dbCnx->prepare("SELECT id, nombre FROM proveedores ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

$proveedorDB = new ProveedorDB();
$proveedores = $proveedorDB->obtenerProveedores();
echo json_encode($proveedores);