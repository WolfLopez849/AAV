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
        header("Location: index.php");
        exit;
    } elseif (isset($_POST["guardar"])) {
        $proveedorDB->insert($nombre, $documento, $telefono, $direccion, $email);
        header("Location: index.php");
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

    header("Location: index.php");
    exit;
}


// Consultar todos los proveedores
$proveedores = $proveedorDB->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Registro de Proveedores</title>
    <!-- Enlace al archivo de estilos CSS -->
    <link rel="stylesheet" href="styles.css" />
    <!-- Enlace a Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
</head>
<body>

    <div class="app-container" id="appContainer">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <!-- Botón de alternancia dentro del logo -->
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <span>POSNOVA</span>
            </div>
            <nav>
                <ul>
                    <li onclick="location.href='../Menu/index.php'"><i class="fas fa-home"></i> <span>Menu principal</span></li>
                    <li onclick="location.href='../inventario/inventario.html'"><i class="fas fa-boxes"></i> <span>Inventario</span></li>
                    <li onclick="location.href='../ventas/index.php'"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></li>
                    <li onclick="location.href='../clientes/clientes.php'"><i class="fas fa-users"></i> <span>Clientes</span></li>
                    <li onclick="location.href='../proveedores/index.php'"><i class="fas fa-truck"></i> <span>Proveedores</span></li>
                    <li onclick="location.href='../caja/index.html'"><i class="fas fa-cash-register"></i> <span>Caja</span></li>
                    <li onclick="location.href='../reportes/index.php'"><i class="fas fa-chart-line"></i> <span>Reportes</span></li>
                    <li onclick="location.href='../usuarios/index.php'"><i class="fas fa-user-cog"></i> <span>Usuarios</span></li>
                    <li onclick="location.href='../configuracion/config.php'"><i class="fas fa-cog"></i> <span>Configuración</span></li>
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="title">
                    <h2><i class="fas fa-truck"></i> <span>Proveedores</span>
                </div>
                <div class="topbar-icons">
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-user-circle"></i>
                    <i class="fas fa-right-from-bracket logout" onclick="location.href='../login/logout.php'"></i>
                </div>
            </header>
            <section>
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
                            <a href="index.php" style="margin-left: 10px;">Cancelar</a>
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
                    <form method="post" action="index.php" style="display:inline;">
                        <input type="hidden" name="editar" value="<?= $row['id'] ?>">
                        <button type="submit" style="background:#32475a; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Editar</button>
                    </form>
                    <!-- Botón Eliminar -->
                    <form method="post" action="index.php" style="display:inline;" onsubmit="return confirm('¿Eliminar este proveedor?');">
                        <input type="hidden" name="eliminar" value="<?= $row['id'] ?>">
                        <button type="submit" style="background:#32475a; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Eliminar</button>
                    </form>
                </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>
        </div>
    </div>

    <!-- Enlace al archivo JavaScript -->
    <script src="main.js"></script>
</body>
</html>
