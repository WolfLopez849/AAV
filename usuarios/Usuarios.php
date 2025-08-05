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

    public function insert($nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por) {
        if (empty($rol)) $rol = 'Administrador';
        if (empty($estado)) $estado = 'activo';
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $this->dbCnx->prepare(
            "INSERT INTO usuarios (nombre_completo, usuario, contrasena, rol, estado, fecha_creacion, creado_por)
             VALUES (?, ?, ?, ?, ?, NOW(), ?)"
        );
        $stmt->execute([$nombre_completo, $usuario, $hash, $rol, $estado, $creado_por]);
    }

    public function update($id, $nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por) {
        if (empty($rol)) $rol = 'Administrador';
        if (empty($estado)) $estado = 'activo';
        if ($contrasena !== "") {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $this->dbCnx->prepare(
                "UPDATE usuarios SET nombre_completo=?, usuario=?, contrasena=?, rol=?, estado=?, creado_por=? WHERE id=?"
            );
            $stmt->execute([$nombre_completo, $usuario, $hash, $rol, $estado, $creado_por, $id]);
        } else {
            $stmt = $this->dbCnx->prepare(
                "UPDATE usuarios SET nombre_completo=?, usuario=?, rol=?, estado=?, creado_por=? WHERE id=?"
            );
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
$usuarios = $usuarioDB->getAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_completo = $_POST["nombre_completo"] ?? '';
    $usuario = $_POST["usuario"] ?? '';
    $contrasena = $_POST["contrasena"] ?? '';
    $rol = $_POST["rol"] ?? '';
    $estado = $_POST["estado"] ?? 'activo';
    $creado_por = $_POST["creado_por"] ?? 'sistema';

    if (isset($_POST['eliminar'])) {
        $usuarioDB->delete($_POST['eliminar']);
        if ($usuarioDB->count() == 0) $usuarioDB->resetAutoIncrement();
        header("Location: Usuarios.php?status=eliminado");
        exit;
    }

    if (isset($_POST["guardar"])) {
        $usuarioDB->insert($nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por);
        header("Location: Usuarios.php?status=creado");
        exit;
    }

    if (isset($_POST["actualizar"])) {
        $usuarioDB->update($_POST["id"], $nombre_completo, $usuario, $contrasena, $rol, $estado, $creado_por);
        header("Location: Usuarios.php?status=actualizado");
        exit;
    }
}
require_once '../caja/config.php';

$estadoCaja = getCajaEstado();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Usuarios - POSNOVA</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<div class="app-container" id="appContainer">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">
      <button class="sidebar-toggle-btn" id="sidebarToggleBtn"><i class="fas fa-bars"></i></button>
      <span>POSNOVA</span>
    </div>
    <nav>
      <ul>
        <li onclick="location.href='../Menu/'"><i class="fas fa-home"></i> <span>Menu principal</span></li>
        <li onclick="location.href='../inventario/inventario.php'"><i class="fas fa-boxes"></i> <span>Inventario</span></li>
        <li onclick="location.href='../ventas/'"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></li>
        <li onclick="location.href='../clientes/clientes.php'"><i class="fas fa-users"></i> <span>Clientes</span></li>
        <li onclick="location.href='../proveedores/'"><i class="fas fa-truck"></i> <span>Proveedores</span></li>
        <li onclick="location.href='<?= ($estadoCaja === 'cerrada') ? '../caja/abrir_caja_final/' : '../caja/' ?>'"><i class="fas fa-cash-register"></i> <span>Caja</span></li>
        <li onclick="location.href='../reportes/reportes.php'"><i class="fas fa-chart-line"></i> <span>Reportes</span></li>
        <li onclick="location.href='../usuarios/Usuarios.php'"><i class="fas fa-user-cog"></i> <span>Usuarios</span></li>
      </ul>
    </nav>
  </aside>

  <!-- Main -->
  <div class="main-content">
    <div class="main-wrapper">
      <header class="topbar">
        <div class="title">
          <h2><i class="fas fa-user-cog"></i> Usuarios</h2>
        </div>
        <div class="topbar-icons">
          
          <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                    </button>
        </div>
      </header>

      <div class="main-panel">
        <div class="panel-header">
          <h3 class="panel-title">Lista de Usuarios</h3>
          
          <div class="action-buttons" id="actionButtons">
            <button class="btn-editar" id="btnEditar" style="display:none">
              <i class="fas fa-edit"></i> Editar
            </button>
            <form method="post" style="display:inline;" id="formEliminar">
              <input type="hidden" name="eliminar" id="eliminarId">
              <button type="submit" class="btn-eliminar" id="btnEliminar" style="display:none">
                <i class="fas fa-trash"></i> Eliminar
              </button>
            </form>
          </div>
          <button class="btn-agregar" id="btnAgregarUsuario">
            <i class="fas fa-user-plus"></i> Agregar Usuario
          </button>
        </div>
        <div class="buscador-pastilla" id="buscadorUsuarios">
          <i class="fas fa-search"></i>
          <input type="text" id="buscarInput" placeholder="Buscar usuario, rol, estado...">
        </div>


        <div style="overflow-x:auto;">
          <table class="tabla-usuarios">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Contraseña</th>
                <th>Fecha Creación</th>
                <th>Creado por</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($usuarios as $u): ?>
                <tr data-id="<?= $u['id'] ?>"
                    data-nombre="<?= htmlspecialchars($u['nombre_completo']) ?>"
                    data-usuario="<?= htmlspecialchars($u['usuario']) ?>"
                    data-rol="<?= htmlspecialchars($u['rol']) ?>"
                    data-estado="<?= htmlspecialchars($u['estado']) ?>"
                    data-creado="<?= htmlspecialchars($u['creado_por']) ?>">
                  <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                  <td><?= htmlspecialchars($u['usuario']) ?></td>
                  <td><?= htmlspecialchars($u['rol']) ?></td>
                  <td><?= htmlspecialchars($u['estado']) ?></td>
                  <td>
                    <div class="campo-password">
                      <input type="password" value="<?= htmlspecialchars($u['contrasena']) ?>" disabled>
                      <button type="button" class="btn-ver" title="Mostrar contraseña">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </td>
                  <td><?= date("d/m/Y", strtotime($u['fecha_creacion'])) ?></td>
                  <td><?= htmlspecialchars($u['creado_por']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL -->
<div id="modalUsuario" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close" id="btnCerrarModal">&times;</span>
    <h2 id="modalTitulo">Nuevo Usuario</h2>
    <form class="formulario-usuario" method="post" id="formUsuario">
      <input type="hidden" name="id" id="idUsuario">

      <div class="form-grid">
        <div>
          <label for="nombre"><i class="fas fa-user"></i> Nombre completo</label>
          <input type="text" name="nombre_completo" id="nombre" placeholder="Ej: Juan Pérez" required>
        </div>
        <div>
          <label for="usuario"><i class="fas fa-id-badge"></i> Nombre de usuario</label>
          <input type="text" name="usuario" id="usuario" placeholder="Ej: juanp" required>
        </div>
      </div>

      <div class="form-grid">
        <div>
          <label for="rol"><i class="fas fa-user-shield"></i> Rol</label>
          <select name="rol" id="rol" required>
            <option value="">Seleccionar</option>
            <option value="Administrador">Administrador</option>
            <option value="Cajero">Cajero</option>
            <option value="Supervisor">Supervisor</option>
          </select>
        </div>
        <div>
          <label for="estado"><i class="fas fa-toggle-on"></i> Estado</label>
          <select name="estado" id="estado" required>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
          </select>
        </div>
      </div>

      <div id="passwordSection">
        <label id="passwordLabel" for="contrasena"><i class="fas fa-lock"></i> Contraseña</label>
        <input type="password" name="contrasena" id="contrasena" placeholder="Escribe una contraseña">
        <button type="button" id="togglePassword">
          <i class="fas fa-eye"></i>
        </button>
      </div>

      <button type="submit" name="guardar" class="btn btn-primary" id="btnSubmit">
        <i class="fas fa-save"></i> Guardar
      </button>
    </form>
  </div>
</div>

<!-- JS externo -->
<script src="script.js"></script>
</body>
</html>
