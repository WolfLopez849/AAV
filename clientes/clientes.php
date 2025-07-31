<?php
require 'conexion.php';
$conexion = obtenerConexion();

$stmt = $conexion->query("SELECT * FROM clientes ORDER BY id DESC");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalClientes = count($clientes);

// Variables para reabrir formulario en caso de error
$showForm = isset($_POST['error']) && $_POST['error'] == 'doc_existente';
$editMode = isset($_POST['edit']) && $_POST['edit'] == 1;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Clientes - POSNOVA</title>
  <link rel="stylesheet" href="estilo_clientes.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body 
  data-mensaje="<?= $_POST['mensaje'] ?? '' ?>" 
  data-tipo="<?= $_POST['tipo'] ?? '' ?>">
<div class="app-container" id="appContainer">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">
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
        <li onclick="location.href='../caja/index.php'"><i class="fas fa-cash-register"></i> <span>Caja</span></li>
        <li onclick="location.href='../reportes/index.php'"><i class="fas fa-chart-line"></i> <span>Reportes</span></li>
        <li onclick="location.href='../usuarios/index.php'"><i class="fas fa-user-cog"></i> <span>Usuarios</span></li>
        <li onclick="location.href='../configuracion/index.php'"><i class="fas fa-cog"></i> <span>Configuración</span></li>
      </ul>
    </nav>
  </aside>

  <!-- Main -->
  <div class="main-content">
    <header class="topbar">
      <div class="title">
        <h2><i class="fas fa-users"></i> Clientes</h2>
      </div>
      <div class="topbar-icons">
        <i class="fas fa-bell"></i>
        <i class="fas fa-user-circle"></i>
        <i class="fas fa-right-from-bracket logout" onclick="location.href='../login/logout.php'"></i>
      </div>
    </header>

    <section class="clientes-seccion">

      <!-- Formulario -->
      <div id="formularioCliente" class="formulario-box" style="display: <?= $showForm ? 'block' : 'none' ?>;">
        <h3 id="tituloFormulario"><?= $editMode ? 'Editar Cliente' : 'Datos del cliente' ?></h3>
        <form method="POST" action="funcionalidades_clientes.php" id="formCliente">
          <input type="hidden" name="id" id="clienteId" value="<?= $_POST['id'] ?? '' ?>" />
          <div class="formulario-cliente">
            <!-- Primera fila -->
             <div class="fila-campos">
              <!-- Nombre -->
               <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" required>
              </div>
              <!-- Tipo de documento (select) -->
               <div class="input-icon">
                <i class="fas fa-id-card"></i>
                <select name="tipo_doc" id="tipo_doc" class="input-field" required>
                  <option value="" disabled selected hidden>Tipo de documento</option>
                  <option value="CC">Cédula de Ciudadanía</option>
                  <option value="CE">Cédula de Extranjería</option>
                  <option value="NIT">NIT</option>
                </select>
              </div>
              <!-- Número de documento -->
               <div class="input-group">
                <i class="fas fa-hashtag"></i>
                <input type="text" name="numero_doc" id="numero_doc" placeholder="Número de documento" required>
              </div>
            </div>
            <!-- Segunda fila -->
             <div class="fila-campos">
              <!-- Teléfono -->
               <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telefono" id="telefono" placeholder="Teléfono">
              </div>
              <!-- Correo -->
               <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" id="correo" placeholder="Correo electrónico">
              </div>
            </div>
          </div>

          <div class="botones-formulario">
            <button type="submit" name="accion" value="<?= $editMode ? 'editar' : 'agregar' ?>" class="btn btn-guardar">
              <i class="fas fa-save"></i> Guardar
            </button>
            <button type="button" onclick="cancelarFormulario()" id="cancelarFormulario" class="btn btn-cancelar">
              <i class="fas fa-times-circle"></i> Cancelar
            </button>
          </div>
        </form>
      </div>

      <!-- Card de clientes -->
      <div class="card-clientes">
        <h3 class="titulo-clientes">Listado de Clientes</h3>
        <p class="descripcion-clientes">
          <?php if ($totalClientes > 0): ?>
            En total tienes <strong><?= $totalClientes ?></strong> cliente<?= $totalClientes > 1 ? 's' : '' ?> en el listado.
          <?php else: ?>
            Aún no tienes clientes agregados. Pulsa en agregar clientes para comenzar.
          <?php endif; ?>
        </p>

        <!-- Acciones -->
        <div class="acciones-clientes fila">
          <button id="btnAgregarCliente" class="btn btn-agregar">
            <i class="fas fa-plus-circle"></i> Agregar Cliente
          </button>
          <button id="btnEditar" class="btn btn-editar" style="display: none;">
            <i class="fas fa-edit"></i> Editar
          </button>
          <button id="btnEliminar" class="btn btn-eliminar" style="display: none;">
            <i class="fas fa-trash-alt"></i> Eliminar
          </button>
        </div>

        <!-- Buscador con icono -->
        <div class="search-bar">
          <i class="fas fa-search search-icon"></i>
          <input type="text" id="buscarCliente" placeholder="Buscar Clientes" />
        </div>

        <!-- Tabla -->
        <table class="tabla-clientes">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Tipo de documento</th>
              <th>Nº Documento</th>
              <th>Teléfono</th>
              <th>Correo</th>
            </tr>
          </thead>
          <tbody id="tbodyClientes">
            <?php if ($clientes): ?>
              <?php foreach ($clientes as $cliente): ?>
                <tr data-id="<?= $cliente['id'] ?>"
                    data-nombre="<?= htmlspecialchars($cliente['nombre']) ?>"
                    data-tipo_doc="<?= htmlspecialchars($cliente['tipo_doc']) ?>"
                    data-numero_doc="<?= htmlspecialchars($cliente['numero_doc']) ?>"
                    data-telefono="<?= htmlspecialchars($cliente['telefono']) ?>"
                    data-correo="<?= htmlspecialchars($cliente['correo']) ?>">
                  <td>
                    <input type="checkbox" class="checkCliente" data-id="<?= $cliente['id'] ?>">
                    <?= htmlspecialchars($cliente['nombre']) ?>
                  </td>
                  <td><?= htmlspecialchars($cliente['tipo_doc']) ?></td>
                  <td><?= htmlspecialchars($cliente['numero_doc']) ?></td>
                  <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                  <td><?= htmlspecialchars($cliente['correo']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5">No hay clientes registrados.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <script src="clientes.js"></script>
  </div>
</div>
</body>
</html>
