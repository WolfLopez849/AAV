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

    public function delete($id) {
        $stmt = $this->dbCnx->prepare("DELETE FROM proveedores WHERE id=?");
        $stmt->execute([$id]);
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
$editando = false;
$edit_id = "";
$nombre = $documento = $telefono = $direccion = $email = "";

// Cargar datos para edición
if (isset($_POST['editar'])) {
    $editando = true;
    $edit_id = $_POST['editar'];
    $datos = $proveedorDB->getById($edit_id);
    if ($datos) {
        $nombre = $datos['nombre'];
        $documento = $datos['documento'];
        $telefono = $datos['telefono'];
        $direccion = $datos['direccion'];
        $email = $datos['email'];
    }
}

// Guardar nuevo o actualizar existente
elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $documento = $_POST["documento"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $direccion = $_POST["direccion"] ?? '';
    $email = $_POST["email"] ?? '';

    if (isset($_POST["actualizar"])) {
        $id_actualizar = $_POST["id"];
        $proveedorDB->update($id_actualizar, $nombre, $documento, $telefono, $direccion, $email);
        header("Location: proveedores.php");
        exit;
    } elseif (isset($_POST["guardar"])) {
        $proveedorDB->insert($nombre, $documento, $telefono, $direccion, $email);
        header("Location: proveedores.php");
        exit;
    }
}

// Eliminar
if (isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    $proveedorDB->delete($id);
    
    if ($proveedorDB->count() == 0) {
        $proveedorDB->resetAutoIncrement();
    }

    header("Location: proveedores.php");
    exit;
}


// Consultar todos los proveedores
$proveedores = $proveedorDB->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Proveedores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #32475a;
            color: white;
            margin: 0;
            padding: 20px;
        }

        .contenedor {
            background-color: #86c1e9;
            padding: 20px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: white;
        }

        label {
            display: block;
            margin-top: 10px;
            color: white;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
        }

        button {
            background-color: #32475a;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            margin-top: 20px;
            background-color: white;
            color: black;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #32475a;
            color: white;
        }

        .acciones a {
            margin-right: 5px;
            color: white;
            background-color: #32475a;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }

        .acciones a:hover {
            background-color: #1f2d3a;
        }
    </style>
</head>
<body>

<h2><?= $editando ? 'Editar Proveedor' : 'Registro de Proveedores' ?></h2>

<div class="contenedor">
    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_id ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($telefono) ?>" required>

        <label>Documento:</label>
        <input type="text" name="documento" value="<?= htmlspecialchars($documento) ?>" required>

        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?= htmlspecialchars($direccion) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <?php if ($editando): ?>
            <button type="submit" name="actualizar">Actualizar</button>
            <a href="proveedores.php" style="margin-left: 10px;">Cancelar</a>
        <?php else: ?>
            <button type="submit" name="guardar">Guardar</button>
        <?php endif; ?>
    </form>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Documento</th>
        <th>Teléfono</th>
        <th>Dirección</th>
        <th>Email</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($proveedores as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['documento']) ?></td>
            <td><?= htmlspecialchars($row['telefono']) ?></td>
            <td><?= htmlspecialchars($row['direccion']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td class="acciones">
    <!-- Botón Editar -->
    <form method="post" action="proveedores.php" style="display:inline;">
        <input type="hidden" name="editar" value="<?= $row['id'] ?>">
        <button type="submit" style="background:#32475a; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Editar</button>
    </form>
    <!-- Botón Eliminar -->
    <form method="post" action="proveedores.php" style="display:inline;" onsubmit="return confirm('¿Eliminar este proveedor?');">
        <input type="hidden" name="eliminar" value="<?= $row['id'] ?>">
        <button type="submit" style="background:#32475a; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Eliminar</button>
    </form>
</td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
