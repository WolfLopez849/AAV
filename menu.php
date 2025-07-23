<?php // menu.php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema POS - Menú Principal</title>
    <link rel="stylesheet" href="styless.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="top-bar">
        <div class="logo">
            <i class="fas fa-store"></i>
            <span>Sistema POS</span>
        </div>
        
        <div class="user-info">
            <div class="notifications">
                <button class="notification-btn" onclick="toggleNotifications(event)">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge"></span>
                </button>
                <div class="notification-dropdown" id="notificationDropdown"></div>
            </div>
            <div class="user-details-group">
                <div class="user-details">
                    <span class="user-name" id="userName">Usuario</span>
                    <span class="user-role" id="userRole">Administrador</span>
                </div>
                <button class="logout-btn" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="navigation-section">
            <h2>Menú Principal</h2>
            <div class="menu-grid">
                <div class="menu-item" onclick="navigateTo('inventario')">
                    <div class="menu-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>Inventario</h3>
                    <p>Gestionar productos y stock</p>
                </div>
                <div class="menu-item" onclick="navigateTo('ventas')">
                    <div class="menu-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Ventas</h3>
                    <p>Procesar ventas y facturas</p>
                </div>
                <div class="menu-item" onclick="navigateTo('clientes')">
                    <div class="menu-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Clientes</h3>
                    <p>Gestionar base de clientes</p>
                </div>
                <div class="menu-item" onclick="navigateTo('proveedores')">
                    <div class="menu-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Proveedores</h3>
                    <p>Administrar proveedores</p>
                </div>
                <div class="menu-item" onclick="navigateTo('usuarios')">
                    <div class="menu-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3>Usuarios</h3>
                    <p>Gestionar usuarios del sistema</p>
                </div>
                <div class="menu-item" onclick="navigateTo('reportes')">
                    <div class="menu-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Reportes</h3>
                    <p>Generar reportes detallados</p>
                </div>
                <div class="menu-item" onclick="navigateTo('caja')">
                    <div class="menu-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <h3>Caja</h3>
                    <p>Control de caja y efectivo</p>
                </div>
                <div class="menu-item" onclick="navigateTo('configuracion')">
                    <div class="menu-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3>Configuración</h3>
                    <p>Configurar sistema</p>
                </div>
            </div>
        </section>

        <section class="reports-section">
            <h2>Reportes Rápidos</h2>
            <div class="charts-grid">
                <div class="chart-container">
                    <h3>Productos Más Vendidos - Mes</h3>
                    <canvas id="topProductsChart"></canvas>
                </div>

                <div class="chart-container">
                    <h3>Productos Más Vendidos - Día</h3>
                    <canvas id="topProductsDayChart"></canvas>
                </div>

                <div class="chart-container">
                    <h3>Ganancias Anuales por Mes</h3>
                    <canvas id="yearlyEarningsChart"></canvas>
                </div>

                <div class="chart-container">
                    <h3>Ventas por Categoría</h3>
                    <canvas id="salesByCategoryChart"></canvas>
                </div>
            </div>
        </section>
    </main>

    <script src="scripts.js"></script>
</body>
</html> 