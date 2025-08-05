<?php
require_once '../Config/conectar.php';

class ProveedorDB extends Conectar {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $stmt = $this->dbCnx->prepare("SELECT * FROM proveedores ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->dbCnx->prepare("SELECT * FROM proveedores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function insert($nombre, $documento, $telefono, $direccion, $email) {
        $stmt = $this->dbCnx->prepare("INSERT INTO proveedores (nombre, documento, telefono, direccion, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $documento, $telefono, $direccion, $email]);
    }

    public function update($id, $nombre, $documento, $telefono, $direccion, $email) {
        $stmt = $this->dbCnx->prepare("UPDATE proveedores SET nombre=?, documento=?, telefono=?, direccion=?, email=? WHERE id=?");
        $stmt->execute([$nombre, $documento, $telefono, $direccion, $email, $id]);
    }

    public function deleteMultiple($ids) {
        $placeholders = rtrim(str_repeat('?,', count($ids)), ',');
        $stmt = $this->dbCnx->prepare("DELETE FROM proveedores WHERE id IN ($placeholders)");
        $stmt->execute($ids);
    }

    public function count() {
        $stmt = $this->dbCnx->query("SELECT COUNT(*) as total FROM proveedores");
        return $stmt->fetch()['total'];
    }

    public function resetAutoIncrement() {
        $this->dbCnx->exec("ALTER TABLE proveedores AUTO_INCREMENT = 1");
    }
}

$proveedorDB = new ProveedorDB();

// Procesar acción del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $proveedorDB->insert(
            $_POST['nombre'],
            $_POST['documento'],
            $_POST['telefono'],
            $_POST['direccion'],
            $_POST['email']
        );
        $mensaje = "Proveedor agregado exitosamente.";
        $tipo = "success";
    } elseif ($accion === 'editar') {
        $proveedorDB->update(
            $_POST['id'],
            $_POST['nombre'],
            $_POST['documento'],
            $_POST['telefono'],
            $_POST['direccion'],
            $_POST['email']
        );
        $mensaje = "Proveedor actualizado correctamente.";
        $tipo = "success";
    } elseif ($accion === 'eliminar') {
        if (!empty($_POST['ids'])) {
            $proveedorDB->deleteMultiple($_POST['ids']);
            if ($proveedorDB->count() == 0) {
                $proveedorDB->resetAutoIncrement();
            }
            $mensaje = "Proveedor(es) eliminado(s) correctamente.";
            $tipo = "success";
        } else {
            $mensaje = "No se seleccionó ningún proveedor.";
            $tipo = "error";
        }
    } else {
        $mensaje = "Acción no válida.";
        $tipo = "error";
    }

 header("Location: index.php?mensaje=" . urlencode($mensaje) . "&tipo=$tipo");
    exit;

}
