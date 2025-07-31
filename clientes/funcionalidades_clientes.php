<?php
require 'conexion.php';
$conexion = obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST['accion'] ?? '';

    function limpiar($valor) {
        return trim(htmlspecialchars($valor));
    }

    // Función para redirigir con POST
    function redirigirConPost($datos) {
        echo "<form id='redirigir' method='POST' action='clientes.php'>";
        foreach ($datos as $clave => $valor) {
            echo "<input type='hidden' name='$clave' value='$valor'>";
        }
        echo "</form>
        <script>document.getElementById('redirigir').submit();</script>";
        exit;
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
                    // Reabrir formulario con datos y mensaje de error
                    redirigirConPost([
                        'error' => 'doc_existente',
                        'mensaje' => 'El número de documento ya está registrado, ingrese otro.',
                        'tipo' => 'error',
                        'nombre' => $nombre,
                        'tipo_doc' => $tipo_doc,
                        'numero_doc' => $numero_doc,
                        'telefono' => $telefono,
                        'correo' => $correo
                    ]);
                }

                // Insertar
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

                redirigirConPost([
                    'mensaje' => 'Cliente agregado correctamente',
                    'tipo' => 'success'
                ]);
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
                // Validar duplicado
                $stmt = $conexion->prepare("SELECT COUNT(*) FROM clientes WHERE numero_doc = :numero_doc AND id != :id");
                $stmt->execute([':numero_doc' => $numero_doc, ':id' => $id]);
                if ($stmt->fetchColumn() > 0) {
                    redirigirConPost([
                        'error' => 'doc_existente',
                        'mensaje' => 'El número de documento ya está registrado en otro cliente.',
                        'tipo' => 'error',
                        'edit' => 1,
                        'id' => $id,
                        'nombre' => $nombre,
                        'tipo_doc' => $tipo_doc,
                        'numero_doc' => $numero_doc,
                        'telefono' => $telefono,
                        'correo' => $correo
                    ]);
                }

                // Actualizar
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

                redirigirConPost([
                    'mensaje' => 'Cliente editado correctamente',
                    'tipo' => 'success'
                ]);
            }
            break;

        case 'eliminar':
            $ids = $_POST['ids'] ?? [];
            if (is_array($ids) && count($ids) > 0) {
                $in = implode(',', array_fill(0, count($ids), '?'));
                $sql = "DELETE FROM clientes WHERE id IN ($in)";
                $stmt = $conexion->prepare($sql);
                $stmt->execute($ids);

                redirigirConPost([
                    'mensaje' => 'Cliente(s) eliminado(s) correctamente',
                    'tipo' => 'success'
                ]);
            }
            break;
    }
}

redirigirConPost(['mensaje' => 'Acción no válida', 'tipo' => 'error']);
