<?php
require_once 'config.php';
setCajaEstado('abierta');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Caja - POSNOVA</title>

  <!-- 1️⃣  Bootstrap 5  -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.1/css/bootstrap.min.css"/>

  <!-- 2️⃣  DataTables (versión Bootstrap) -->
  <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>

  <!-- 3️⃣  Font Awesome (iconos) -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <!-- 4️⃣  Tu hoja de estilos POSNOVA  -->
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
<div class="app-container" id="appContainer">

  <!-- ░░ Sidebar ░░ -->
  <aside class="sidebar" id="sidebar">
    <div class="logo">
      <button id="sidebarToggleBtn" class="btn-icon">
        <i class="fas fa-bars"></i>
      </button>
      <i class="fas fa-store"></i><span>POSNOVA</span>
    </div>

    <nav>
      <ul>
        <li onclick="location.href='../Menu/'">
          <i class="fas fa-table-cells-large"></i><span>Menu principal</span>
        </li>
        <li onclick="location.href='../inventario/inventario.php'">
          <i class="fas fa-boxes-stacked"></i><span>Inventario</span>
        </li>
        <li onclick="location.href='../ventas/'">
          <i class="fas fa-cart-shopping"></i><span>Ventas</span>
        </li>
        <li onclick="location.href='../clientes/clientes.php'">
          <i class="fas fa-user-group"></i><span>Clientes</span>
        </li>
        <li onclick="location.href='../proveedores/'">
          <i class="fas fa-truck-fast"></i><span>Proveedores</span>
        </li>
        <li onclick="location.href=''">
          <i class="fas fa-cash-register"></i><span>Caja</span>
        </li>
        <li onclick="location.href='../reportes/reportes.php'">
          <i class="fas fa-chart-line"></i><span>Reportes</span>
        </li>
        <li onclick="location.href='../usuarios/Usuarios.php'">
          <i class="fas fa-user-gear"></i><span>Usuarios</span>
        </li>
        
      </ul>
    </nav>
  </aside>

  <!-- ░░ Main ░░ -->
  <div class="main-content">
    <header class="topbar">
      <h1><i class="fas fa-cash-register"></i> Registro Venta</h1>
      <div class="top-icons">
        <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                    </button>
      </div>
    </header>

    <!-- Contenido -->
    <main class="content">
      <div class="card">

        <!-- =========  FORMULARIO ========= -->
        <form id="productForm" autocomplete="off">

          <!-- ● Datos del cliente -->
          <section class="form-section">
            <h3><i class="fas fa-user"></i> Datos del cliente</h3>

            <div class="form-grid">
              <input  id="pNameProd"  class="double" type="text"
                      placeholder="Nombre completo *" required>
              <select id="pDocType" required>
                <option value="">Tipo de documento *</option>
                <option value="CC">Cédula de ciudadanía</option>
                <option value="CE">Cédula extranjera</option>
                <option value="PAS">Pasaporte</option>
              </select>
              <input id="pDocNum" type="text" placeholder="No. documento *" required min="1000000" pattern="\d{7,}" inputmode="numeric" style="appearance: textfield;" />              <input  id="pEmail"     type="email" placeholder="Correo electrónico">
              <input  id="pTel"       type="text" placeholder="Teléfono">
            </div>

            <div class="form-actions">
              <button type="button" id="newClientBtn" class="btn btn-secondary">
                <i class="fas fa-eraser"></i> Limpiar cliente
              </button>
            </div>
          </section>

          <!-- ● Datos del producto -->
          <section class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h3 class="mb-0"><i class="fas fa-box"></i> Datos del producto</h3>
              <button type="button" id="searchProductBtn" class="btn btn-primary btn-sm">
                <i class="fas fa-magnifying-glass"></i> Buscar producto
              </button>
            </div>

            <div class="form-grid">
              <input type="hidden" id="pId">
              <input  id="pCode"       type="text"   placeholder="Código *" readonly required>
              <input  id="pName" class="double" type="text" placeholder="Nombre" readonly required>
              <input  id="pPrice"      type="number" step="0.01" placeholder="Precio compra *" readonly required>
              <input id="pQty"   type="number" min="1" value="1" placeholder="Cant." required>
              <input  id="pIVA"       type="number" min="0" placeholder="IVA %" readonly required>
              <input  id="pDate" type="date" required readonly>
              <input  id="pCategory"   type="text" placeholder="Categoría" readonly required>
              <select id="pPaymentMethod" required>
                <option value="">Método de pago</option>
                <option>Efectivo</option><option>Tarjeta</option>
                <option>Transferencia</option><option>QR / Móvil</option>
                <option>Crédito</option>
              </select>
              <input  id="pTotal"      type="number" step="0.01"
                       placeholder="Total (auto)" readonly>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-circle-plus"></i> Agregar producto
              </button>
            </div>
          </section>
        </form>

        <!-- =========  TABLA ========= -->
        <div class="table-responsive mt-3">
          <table id="productTable" class="table table-striped w-100">
            <thead>
              <tr>
                <th>ID</th><th>Código</th><th>Nombre</th><th>Precio compra</th>
                <th>Cant.</th>
                <th>IVA %</th><th>Fecha</th><th>Categoría</th>
                <th>Método</th><th>Total</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <!-- =========  ACCIONES ========= -->
        <div class="actions-row">
  <!-- NUEVO: botón eliminar -->
  <button id="deleteRowBtn" class="btn btn-danger" disabled>
    <i class="fas fa-trash-alt"></i> Eliminar producto
  </button>

  <button id="closeCashBtn" class="btn btn-secondary">
    <i class="fas fa-cash-register"></i> Cierre de caja
  </button>

  <button id="registerSaleBtn" class="btn btn-primary">
    <i class="fas fa-receipt"></i> Registrar venta
  </button>
</div>

<!-- =========  Modal Bootstrap ========= -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar producto</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table id="searchTable" class="table table-hover w-100">
          <thead>
            <tr><th>id</th><th>Código</th><th>Nombre</th><th>Categoría</th><th>Precio compra</th><th>IVA%</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- =========  JS ========= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="main.js"></script>
<script src=""></script>
</body>
</html>
