<?php
session_start();

function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getUserInfo() {
    if (isUserLoggedIn()) {
        return [
            'name' => $_SESSION['user_name'] ?? 'Usuario',
            'role' => $_SESSION['user_role'] ?? 'Administrador'
        ];
    }
    return [
        'name' => 'Usuario',
        'role' => 'Administrador'
    ];
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}

$userInfo = getUserInfo();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema POS - Menú Principal</title>
    <link rel="stylesheet" href="styless.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">  
</head>
<body>
    <nav class="navbar navbar-light bg-white fixed-top">
        <div class="container-fluid">

            <div class="d-flex align-items-center">

                <div class="logo d-flex align-items-center">
                    <i class="fas fa-store"></i>
                    <span class="ms-2">Sistema POS</span>
                </div>
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
                        <i class="fas fa-sign-out-alt me-2"></i>
                    </button>
                </div>
                
            </div>

        </div>
    </nav>

    <main class="main-content">
        <section class="navigation-section">
            <h2>Menú Principal</h2>
            <div class="menu-grid">
                <div class="menu-item" onclick="location.href='../inventario/inventario.html'">
                    <div class="menu-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>Inventario</h3>
                    <p>Gestionar productos y stock</p>
                </div>
                <div class="menu-item" onclick="location.href='../ventas/index.php'">
                    <div class="menu-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Ventas</h3>
                    <p>Procesar ventas y facturas</p>
                </div>
                <div class="menu-item" onclick="location.href='../clientes/clientes.php'">
                    <div class="menu-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Clientes</h3>
                    <p>Gestionar base de clientes</p>
                </div>
                <div class="menu-item" onclick="location.href='../proveedores/index.php'">
                    <div class="menu-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Proveedores</h3>
                    <p>Administrar proveedores</p>
                </div>
                <div class="menu-item" onclick="location.href='../usuarios/index.php'">
                    <div class="menu-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3>Usuarios</h3>
                    <p>Gestionar usuarios del sistema</p>
                </div>
                <div class="menu-item" onclick="location.href='../reportes/index.php'">
                    <div class="menu-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Reportes</h3>
                    <p>Generar reportes detallados</p>
                </div>
                <div class="menu-item" onclick="location.href='../caja/index.html'">
                    <div class="menu-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <h3>Caja</h3>
                    <p>Control de caja y efectivo</p>
                </div>
                <div class="menu-item" onclick="location.href='../configuracion/index.php'">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
   
</body>
</html>