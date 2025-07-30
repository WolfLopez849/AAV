<?php
require 'conexion.php';
$conexion = obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST['accion'] ?? '';

    function limpiar($valor) {
        return trim(htmlspecialchars($valor));
    }

    switch ($accion) {
        case 'agregar':
            $nombre     = limpiar($_POST['nombre'] ?? '');
            $tipo_doc   = limpiar($_POST['tipo_doc'] ?? '');
            $numero_doc = limpiar($_POST['numero_doc'] ?? '');
            $telefono   = limpiar($_POST['telefono'] ?? '');
            $correo     = limpiar($_POST['correo'] ?? '');

            if ($nombre && $tipo_doc && $numero_doc) {
                // Validar si el número de documento ya existe
                $stmt = $conexion->prepare("SELECT COUNT(*) FROM clientes WHERE numero_doc = :numero_doc");
                $stmt->execute([':numero_doc' => $numero_doc]);
                if ($stmt->fetchColumn() > 0) {
                    // Devolver error sin cerrar el formulario
                    header("Location: clientes.php?error=doc_existente&nombre={$nombre}&tipo_doc={$tipo_doc}&numero_doc={$numero_doc}&telefono={$telefono}&correo={$correo}");
                    exit;
                }

                // Si no existe, insertamos
                $sql = "INSERT INTO clientes (nombre, tipo_doc, numero_doc, telefono, correo)
                        VALUES (:nombre, :tipo_doc, :numero_doc, :telefono, :correo)";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([
                    ':nombre'     => $nombre,
                    ':tipo_doc'   => $tipo_doc,
                    ':numero_doc' => $numero_doc,
                    ':telefono'   => $telefono,
                    ':correo'     => $correo
                ]);
                header("Location: clientes.php?exito=1");
                exit;
            }
            break;

        case 'editar':
            $id         = limpiar($_POST['id'] ?? '');
            $nombre     = limpiar($_POST['nombre'] ?? '');
            $tipo_doc   = limpiar($_POST['tipo_doc'] ?? '');
            $numero_doc = limpiar($_POST['numero_doc'] ?? '');
            $telefono   = limpiar($_POST['telefono'] ?? '');
            $correo     = limpiar($_POST['correo'] ?? '');

            if ($id && $nombre && $tipo_doc && $numero_doc) {
                // Validar si el número de documento ya existe para otro cliente
                $stmt = $conexion->prepare("SELECT COUNT(*) FROM clientes WHERE numero_doc = :numero_doc AND id != :id");
                $stmt->execute([':numero_doc' => $numero_doc, ':id' => $id]);
                if ($stmt->fetchColumn() > 0) {
                    header("Location: clientes.php?error=doc_existente&edit=1&id={$id}&nombre={$nombre}&tipo_doc={$tipo_doc}&numero_doc={$numero_doc}&telefono={$telefono}&correo={$correo}");
                    exit;
                }

                $sql = "UPDATE clientes SET nombre = :nombre, tipo_doc = :tipo_doc, numero_doc = :numero_doc,
                        telefono = :telefono, correo = :correo WHERE id = :id";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([
                    ':id'         => $id,
                    ':nombre'     => $nombre,
                    ':tipo_doc'   => $tipo_doc,
                    ':numero_doc' => $numero_doc,
                    ':telefono'   => $telefono,
                    ':correo'     => $correo
                ]);
                header("Location: clientes.php?editado=1");
                exit;
            }
            break;

        case 'eliminar':
            $ids = $_POST['ids'] ?? [];
            if (is_array($ids) && count($ids) > 0) {
                $in = implode(',', array_fill(0, count($ids), '?'));
                $sql = "DELETE FROM clientes WHERE id IN ($in)";
                $stmt = $conexion->prepare($sql);
                $stmt->execute($ids);
                header("Location: clientes.php?eliminado=1");
                exit;
            }
            break;
    }
}
header("Location: clientes.php");
exit;
