<?php
require_once '../Config/conectar.php';

class UsuarioDB extends Conectar {
    public function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $stmt = $this->dbCnx->prepare("SELECT * FROM usuarios ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->dbCnx->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function insert($nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $this->dbCnx->prepare("INSERT INTO usuarios (nombre_completo, usuario, contrasena, rol, estado, fecha_creacion, creado_por) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->execute([$nombre_completo, $usuario, $hash, $rol, $estado, $creado_por]);
    }

    public function update($id, $nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por) {
        if ($contrasena !== "") {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $this->dbCnx->prepare("UPDATE usuarios SET nombre_completo=?, usuario=?, contrasena=?, rol=?, estado=?, creado_por=? WHERE id=?");
            $stmt->execute([$nombre_completo, $usuario, $hash, $rol, $estado, $creado_por, $id]);
        } else {
            $stmt = $this->dbCnx->prepare("UPDATE usuarios SET nombre_completo=?, usuario=?, rol=?, estado=?, creado_por=? WHERE id=?");
            $stmt->execute([$nombre_completo, $usuario, $rol, $estado, $creado_por, $id]);
        }
    }

    public function delete($id) {
        $stmt = $this->dbCnx->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->execute([$id]);
    }

    public function count() {
        $stmt = $this->dbCnx->query("SELECT COUNT(*) as total FROM usuarios");
        return $stmt->fetch()['total'];
    }

    public function resetAutoIncrement() {
        $this->dbCnx->exec("ALTER TABLE usuarios AUTO_INCREMENT = 1");
    }
}

$usuarioDB = new UsuarioDB();
$editando = false;
$edit_id = "";
$nombre_completo = $usuario = $contrasena = $rol = $estado = $creado_por = "";

// Cargar datos para edición
if (isset($_POST['editar'])) {
    $editando = true;
    $edit_id = $_POST['editar'];
    $datos = $usuarioDB->getById($edit_id);
    if ($datos) {
        $nombre_completo = $datos['nombre_completo'];
        $usuario = $datos['usuario'];
        $contrasena = $datos['contrasena'];
        $rol = $datos['rol'];
        $estado = $datos['estado'];
        $creado_por = $datos['creado_por'];
    }
}

// Guardar nuevo o actualizar existente
elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_completo = $_POST["nombre_completo"] ?? '';
    $usuario = $_POST["usuario"] ?? '';
    $contrasena = $_POST["contrasena"] ?? '';
    $rol = $_POST["rol"] ?? '';
    $estado = $_POST["estado"] ?? 'Activo';
    $creado_por = $_POST["creado_por"] ?? 'sistema';

    if (isset($_POST["actualizar"])) {
        $id_actualizar = $_POST["id"];
        $usuarioDB->update($id_actualizar, $nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por);
        header("Location: Usuarios.php");
        exit;
    } elseif (isset($_POST["guardar"])) {
        $usuarioDB->insert($nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por);
        header("Location: Usuarios.php");
        exit;
    }
}

// Eliminar
if (isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    $usuarioDB->delete($id);

    if ($usuarioDB->count() == 0) {
        $usuarioDB->resetAutoIncrement();
    }

    header("Location: Usuarios.php");
    exit;
}

// Consultar todos los usuarios
$usuarios = $usuarioDB->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="styless.css"/>
    <link rel="stylesheet" href="styles.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div class="app-container" id="appContainer">
        <aside class="sidebar">
            <div class="logo">
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <span>POSNOVA</span>
            </div>
            <nav>
                <ul>
                    <li onclick="location.href='../Menu/index.php'"><i class="fas fa-home"></i> <span>Menú principal</span></li>
                    <li onclick="location.href='../inventario/inventario.html'"><i class="fas fa-boxes"></i> <span>Inventario</span></li>
                    <li onclick="location.href='../ventas/index.php'"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></li>
                    <li onclick="location.href='../clientes/clientes.php'"><i class="fas fa-users"></i> <span>Clientes</span></li>
                    <li onclick="location.href='../proveedores/index.php'"><i class="fas fa-truck"></i> <span>Proveedores</span></li>
                    <li onclick="location.href='../caja/index.html'"><i class="fas fa-cash-register"></i> <span>Caja</span></li>
                    <li onclick="location.href='../reportes/index.php'"><i class="fas fa-chart-line"></i> <span>Reportes</span></li>
                    <li onclick="location.href='../usuarios/Usuarios.php'"><i class="fas fa-user-cog"></i> <span>Usuarios</span></li>
                    <li onclick="location.href='../configuracion/config.php'"><i class="fas fa-cog"></i> <span>Configuración</span></li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <header class="topbar">
                <div class="title">
                    <h2><i class="fas fa-user-cog"></i> <span>Usuarios</span></h2>
                </div>
                <div class="topbar-icons">
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-user-circle"></i>
                    <i class="fas fa-right-from-bracket logout" onclick="location.href='../login/logout.php'"></i>
                </div>
            </header>
            <section>
                <h2><?= $editando ? 'Editar Usuario' : 'Registro de Usuarios' ?></h2>
                <div class="contenedor">
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $edit_id ?>">

                        <label>Nombre completo:</label>
                        <input type="text" name="nombre_completo" value="<?= htmlspecialchars($nombre_completo) ?>" required>

                        <label>Usuario:</label>
                        <input type="text" name="usuario" value="<?= htmlspecialchars($usuario) ?>" required>

                        <label>Contraseña:</label>
                        <div style="position:relative; display:flex; align-items:center;">
                            <input type="password" name="contrasena" id="contrasenaInput" value="<?= htmlspecialchars($contrasena) ?>" required style="padding-right:50px;">
                            <button type="button" id="togglePwd" style=" right:10px; color:black; background:none; padding:5px; border:none; cursor:pointer;">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-row-modal-horizontal">
                            <label>Rol:</label>
                            <select name="rol" class="input-field select-rol" required>
                                <option value=""></option>
                                <option value="Administrador" <?= $rol=="Administrador"?"selected":"" ?>>Administrador</option>
                                <option value="Cajero" <?= $rol=="Cajero"?"selected":"" ?>>Cajero</option>
                                <option value="Supervisor" <?= $rol=="Supervisor"?"selected":"" ?>>Supervisor</option>
                            </select>

                            <label>Estado:</label>
                            <select name="estado" class="input-field select-rol" required>
                                <option value=""></option>
                                <option value="Activo" <?= $estado=="activo"?"selected":"" ?>>Activo</option>
                                <option value="Inactivo" <?= $estado=="inactivo"?"selected":"" ?>>Inactivo</option>
                            </select>

                            <label>Creado por:</label>
                            <select name="creado_por" class="input-field select-rol" required>
                                <option value=""></option>
                                <option value="Administrador" <?= $creado_por=="Administrador"?"selected":"" ?>>Administrador</option>
                                <option value="Supervisor" <?= $creado_por=="Supervisor"?"selected":"" ?>>Supervisor</option>
                                <option value="sistema" <?= $creado_por=="sistema"?"selected":"" ?>>Sistema</option>
                            </select>
                        </div>
                        

                        <?php if ($editando): ?>
                            <button type="submit" name="actualizar">Actualizar</button>
                            <a href="Usuarios.php" style="margin-left: 10px;">Cancelar</a>
                        <?php else: ?>
                            <button type="submit" name="guardar">Guardar</button>
                        <?php endif; ?>
                    </form>
                </div>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nombre completo</th>
                        <th>Usuario</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha creación</th>
                        <th>Creado por</th>
                        <th>Acciones</th>
                    </tr>
                    <?php foreach ($usuarios as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nombre_completo']) ?></td>
                            <td><?= htmlspecialchars($row['usuario']) ?></td>
                            <td><?= htmlspecialchars($row['contrasena']) ?></td>
                            <td><?= htmlspecialchars($row['rol']) ?></td>
                            <td><?= htmlspecialchars($row['estado']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_creacion']) ?></td>
                            <td><?= htmlspecialchars($row['creado_por']) ?></td>
                            <td class="acciones">
                                <form method="post" action="Usuarios.php" style="display:inline;">
                                    <input type="hidden" name="editar" value="<?= $row['id'] ?>">
                                    <button type="submit" style="background:#32475a; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Editar</button>
                                </form>
                                <form method="post" action="Usuarios.php" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
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
    <script src="main.js"></script>
    <script src="script.js"></script>
</body>
</html>