<?php
require_once '../config.php';
setCajaEstado('cerrada');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Caja Apertura - POSNOVA</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">  
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
                    <li onclick="location.href='../../Menu/'"><i class="fas fa-table-cells-large"></i><span>Menu principal</span></li>
                    <li onclick="location.href='../../inventario/inventario.php'"><i class="fas fa-boxes-stacked"></i><span>Inventario</span></li>
                    <li onclick="location.href='../../ventas/'"><i class="fas fa-cart-shopping"></i><span>Ventas</span></li>
                    <li onclick="location.href='../../clientes/clientes.php'"><i class="fas fa-user-group"></i><span>Clientes</span></li>
                    <li onclick="location.href='../../proveedores/'"><i class="fas fa-truck-fast"></i><span>Proveedores</span></li>
                    <li onclick="location.href=''"><i class="fas fa-cash-register"></i><span>Caja</span></li>
                    <li onclick="location.href='../../reportes/reportes.php'"><i class="fas fa-chart-line"></i><span>Reportes</span></li>
                    <li onclick="location.href='../../usuarios/Usuarios.php'"><i class="fas fa-user-gear"></i><span>Usuarios</span></li>
                </ul>
            </nav>
        </aside>
        <div class="main-content">
            <header class="topbar">
                <div class="title">
                    <h2><i class="fas fa-cash-register"></i>Apertura de Caja</h2>
                </div>
                <div class="topbar-icons">
                    <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                    </button>                </div>
            </header>
            <section>
                <div class="datetime-box">
                  <span id="datetime" class="datetime">Cargando fecha y hora actuales...</span>
                </div>
                <section class="form-section">
                    <form class="form" method="POST">
                        <label>Id del empleado:</label>
                        <input type="text" value="AUTOMATICO" readonly />
                        <label>Monto Inicial:</label>
                        <input type="text" id="initialAmount" name="monto_inicial" placeholder="0.00" />
                        <button type="submit" name="abrir_caja" id="openRegisterBtn">Abrir caja</button>
                    </form>
                </section>
            </section>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>