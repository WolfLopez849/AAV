<?php
require_once '../caja/config.php';

$estadoCaja = getCajaEstado();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reportes - POSNOVA</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

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

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="title">
                <h2><i class="fas fa-chart-line"></i> Reportes </h2>
            </div>
            <div class="topbar-icons">
<button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                    </button>            
                </div>
        </header>

        <!-- Filtros de fecha -->
        <div class="filtros-fecha">
            <label for="tipo-filtro">Filtrar por:</label>
            <select id="tipo-filtro">
                <option value="dia">Día específico</option>
                <option value="mes">Mes y Año</option>
                <option value="anio">Año</option>
            </select>

            <input type="date" id="filtro-dia" style="display:none;">
            <input type="month" id="filtro-mes" style="display:none;">
            <input type="number" id="filtro-anio" min="2000" max="2100" placeholder="Año" style="display:none;">

            <button id="btn-filtrar">Aplicar filtro</button>
        </div>


        <!-- Sección de reportes -->
        <section class="reportes">
            <!-- Tarjetas resumen -->
            <div class="resumen-ventas">
                <div class="card-resumen">
                    <h3>Ventas Hoy</h3>
                    <p id="total-hoy">$0</p>
                </div>
                <div class="card-resumen">
                    <h3>Ventas Semana</h3>
                    <p id="total-semana">$0</p>
                </div>
                <div class="card-resumen">
                    <h3>Ventas Mes</h3>
                    <p id="total-mes">$0</p>
                </div>
                <div class="card-resumen">
                    <h3>Crecimiento</h3>
                    <p id="crecimiento">0%</p>
                </div>
            </div>

            <!-- Tarjetas de ingresos y ganancias/pérdidas -->
            <div class="resumen-ventas">
                <div class="card-resumen">
                    <h3>Ingresos Totales</h3>
                    <p id="ingresos-totales">$0</p>
                </div>
                <div class="card-resumen">
                    <h3>Ganancias</h3>
                    <p id="ganancias">$0</p>
                </div>
                <div class="card-resumen">
                    <h3>Pérdidas</h3>
                    <p id="perdidas">$0</p>
                </div>
            </div>

            <!-- Tabla detallada -->
            <table class="tabla-ventas">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Productos</th>
                        <th>Ingresos</th>
                        <th>Costos</th>
                        <th>Ganancia/Pérdida</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <!-- Se llena con JS -->
                </tbody>
            </table>

            <!-- Gráficos -->
            <div class="graficos">
                <div style="flex:1">
                    <h4>Distribución de Ventas</h4>
                    <canvas id="graficoCircular"></canvas>
                </div>
                <div style="flex:2">
                    <h4>Evolución de Ventas</h4>
                    <canvas id="graficoColumnas"></canvas>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Script de reportes -->
<script src="main.js"></script>
<script src="reportes.js"></script>
</body>
</html>
