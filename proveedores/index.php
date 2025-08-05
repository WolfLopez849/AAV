<?php
require_once '../Config/conectar.php';
require_once 'funcionalidades_proveedores.php';

$proveedorDB = new ProveedorDB();
$proveedores = $proveedorDB->getAll();
$total = count($proveedores);

require_once '../caja/config.php';

$estadoCaja = getCajaEstado();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Proveedores - POSNOVA</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body
  <?php if (isset($_GET['mensaje']) && isset($_GET['tipo'])): ?>
    data-mensaje="<?= htmlspecialchars(urldecode($_GET['mensaje'])) ?>"
    data-tipo="<?= htmlspecialchars($_GET['tipo']) ?>"
  <?php endif; ?>
>
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
        <li onclick="location.href='../Menu/'"><i class="fas fa-home"></i><span> Menu principal</span></li>
        <li onclick="location.href='../inventario/inventario.php'"><i class="fas fa-boxes"></i><span> Inventario</span></li>
        <li onclick="location.href='../ventas/'"><i class="fas fa-shopping-cart"></i><span> Ventas</span></li>
        <li onclick="location.href='../clientes/clientes.php'"><i class="fas fa-users"></i><span> Clientes</span></li>
        <li onclick="location.href='../proveedores/'"><i class="fas fa-truck"></i><span> Proveedores</span></li>
        <li onclick="location.href='<?= ($estadoCaja === 'cerrada') ? '../caja/abrir_caja_final/' : '../caja/' ?>'"><i class="fas fa-cash-register"></i><span> Caja</span></li>
        <li onclick="location.href='../reportes/reportes.php'"><i class="fas fa-chart-line"></i><span> Reportes</span></li>
        <li onclick="location.href='../usuarios/Usuarios.php'"><i class="fas fa-user-cog"></i><span> Usuarios</span></li>
      </ul>
    </nav>
  </aside>

  <div class="main-content">
    <header class="topbar">
      <div class="title">
        <h2><i class="fas fa-truck"></i> Proveedores</h2>
      </div>
      <div class="topbar-icons">
<button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                    </button>      </div>
    </header>

    <section class="clientes-seccion">

      <!-- Formulario dentro de card -->
      <div id="formularioProveedor" class="card-clientes formulario-cliente ocultar">
        <h3 id="tituloFormulario">Datos del proveedor</h3>
        <form method="POST" action="funcionalidades_proveedores.php" id="formProveedor">
          <input type="hidden" name="id" id="proveedorId">
          <input type="hidden" name="accion" value="agregar" id="accionFormulario">
          <div class="formulario-cliente">
            <div class="fila-campos">
              <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del proveedor" required>
              </div>
              <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="documento" id="documento" placeholder="Documento" required>
              </div>
            </div>
            <div class="fila-campos">
              <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telefono" id="telefono" placeholder="Teléfono">
              </div>
              <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección">
              </div>
            </div>
            <div class="fila-campos">
              <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Correo electrónico">
              </div>
            </div>
          </div>

          <div class="botones-formulario">
            <button type="submit" class="btn btn-guardar">
              <i class="fas fa-save"></i> Guardar
            </button>
            <button type="button" onclick="cancelarFormulario()" id="cancelarFormulario" class="btn btn-cancelar">
              <i class="fas fa-times-circle"></i> Cancelar
            </button>
          </div>
        </form>
      </div>

      <!-- Listado de proveedores -->
      <div class="card-clientes">
        <h3 class="titulo-clientes">Listado de Proveedores</h3>
        <p class="descripcion-clientes">
          <?= $total > 0 ? "Tienes <strong>$total</strong> proveedor(es)." : "Aún no hay proveedores registrados." ?>
        </p>

        <div class="acciones-clientes fila">
          <button id="btnAgregarProveedor" class="btn btn-agregar">
            <i class="fas fa-plus-circle"></i> Agregar Proveedor
          </button>
          <button id="btnEditarProveedor" class="btn btn-editar" style="display: none;">
            <i class="fas fa-edit"></i> Editar
          </button>
          <button id="btnEliminarProveedor" class="btn btn-eliminar" style="display: none;">
            <i class="fas fa-trash-alt"></i> Eliminar
          </button>
        </div>

        <div class="search-bar">
          <i class="fas fa-search"></i>
          <input type="text" id="buscarProveedor" placeholder="Buscar Proveedores">
        </div>

        <table class="tabla-clientes">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Documento</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>Correo</th>
            </tr>
          </thead>
          <tbody id="tbodyProveedores">
            <?php foreach ($proveedores as $proveedor): ?>
              <tr data-id="<?= $proveedor['id'] ?>"
                  data-nombre="<?= htmlspecialchars($proveedor['nombre']) ?>"
                  data-documento="<?= htmlspecialchars($proveedor['documento']) ?>"
                  data-telefono="<?= htmlspecialchars($proveedor['telefono']) ?>"
                  data-direccion="<?= htmlspecialchars($proveedor['direccion']) ?>"
                  data-email="<?= htmlspecialchars($proveedor['email']) ?>">
                <td>
                  <input type="checkbox" class="checkProveedor" data-id="<?= $proveedor['id'] ?>">
                  <?= htmlspecialchars($proveedor['nombre']) ?>
                </td>
                <td><?= htmlspecialchars($proveedor['documento']) ?></td>
                <td><?= htmlspecialchars($proveedor['telefono']) ?></td>
                <td><?= htmlspecialchars($proveedor['direccion']) ?></td>
                <td><?= htmlspecialchars($proveedor['email']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</div>

<script src="main.js"></script>
</body>
</html>
