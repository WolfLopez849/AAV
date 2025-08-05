<?php
require '../caja/config.php';

$fecha = $_GET['fecha'] ?? date('Y-m-d'); // ← aquí pones la fecha de hoy por defecto
$filtro = trim($_GET['filtro'] ?? '');

$where = [];
$params = [];

if ($fecha) {
    $where[] = "DATE(created_at) = ?";
    $params[] = $fecha;
}
if ($filtro) {
    $where[] = "(name_client LIKE ? OR code LIKE ? OR document LIKE ?)";
    $params[] = "%$filtro%";
    $params[] = "%$filtro%";
    $params[] = "%$filtro%";
}

$sql = "SELECT * FROM caja";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../caja/config.php';

$estadoCaja = getCajaEstado();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Ventas de caja - POSNOVA</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    
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
        <div class="main-content">
            <header class="topbar">
                <div class="title">
                    <h2><i class="fas fa-shopping-cart"></i>Ventas de caja</h2>
                </div>
                <div class="topbar-icons">
                    <button class="logout-btn" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                    </button>
                </div>
            </header>
            <div class="container">
                <div class="header-section">
                    <div class="search-controls">
                        <form id="filtroForm" method="get" style="display:inline;">
                            <label for="searchFilter">Buscar:</label>
                            <input type="text" id="searchFilter" name="filtro" value="<?= htmlspecialchars($filtro ?? '') ?>" placeholder="Código o cliente...">
                            <input type="date" id="fechaFiltro" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
                            <button type="submit" class="filter-btn"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <div class="action-buttons">
                        <button class="icon-btn pdf-btn"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
                        <button class="icon-btn excel-btn"><i class="fas fa-file-excel"></i> Exportar Excel</button>
                    </div>
                </div>
               
                <div class="table-responsive" >
                    <table id="ventasTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Documento</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>IVA %</th>
                                <th>Total Línea</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        
                           
                            <tbody id="salesTableBody">
                                <?php foreach ($ventas as $i => $row): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= htmlspecialchars($row['name_client']) ?></td>
                                    <td><?= htmlspecialchars($row['document']) ?></td>
                                    <td><?= htmlspecialchars($row['code']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['category']) ?></td>
                                    <td><?= htmlspecialchars($row['qty']) ?></td>
                                    <td><?= number_format($row['price_unit'], 2) ?></td>
                                    <td><?= number_format($row['iva_percent'], 2) ?></td>
                                    <td><?= number_format($row['total_line'], 2) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                    </table>
                </div>
                <div class="pagination-summary">
                    <div class="pagination">
                        <button id="prevPage" disabled><i class="fas fa-chevron-left"></i> Anterior</button>
                        <span id="pageInfo">Página 1</span>
                        <button id="nextPage">Siguiente <i class="fas fa-chevron-right"></i></button>
                        <span>Total de ventas: <?= count($ventas) ?></span>  
                    </div>
                    <div class="summary">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ROWS_PER_PAGE = 10;
        let currentPage = 1;
        const ventasData = <?php echo json_encode($ventas); ?>;
        let totalRows = ventasData.length;
        let totalPages = Math.max(1, Math.ceil(totalRows / ROWS_PER_PAGE));

        function exportTableToExcel(tableID, filename = 'reporte_' + new Date().toISOString().slice(0, 10) + '.xlsx') {
            const table = document.getElementById(tableID);
            let tableHTML = table.outerHTML.replace(/ /g, '%20');
            const a = document.createElement('a');
            a.href = 'data:application/vnd.ms-excel,' + tableHTML;
            a.download = filename;
            a.click();
        }

        function renderTable(page) {
            const tbody = document.getElementById('salesTableBody');
            tbody.innerHTML = '';
            const start = (page - 1) * ROWS_PER_PAGE;
            const end = Math.min(start + ROWS_PER_PAGE, ventasData.length);
            for (let i = start; i < end; i++) {
                const row = ventasData[i];
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${i + 1}</td>
                    <td>${row.name_client}</td>
                    <td>${row.document}</td>
                    <td>${row.code}</td>
                    <td>${row.name}</td>
                    <td>${row.category}</td>
                    <td>${row.qty}</td>
                    <td>${Number(row.price_unit).toFixed(2)}</td>
                    <td>${Number(row.iva_percent).toFixed(2)}</td>
                    <td>${Number(row.total_line).toFixed(2)}</td>
                    <td>${row.created_at}</td>
                `;
                tbody.appendChild(tr);
            }
            document.getElementById('pageInfo').textContent = `Página ${page} de ${totalPages}`;
            document.getElementById('prevPage').disabled = page === 1;
            document.getElementById('nextPage').disabled = page === totalPages;
        }
            document.querySelector('.excel-btn').addEventListener('click', function() {
                // Construye una tabla temporal con todas las ventas filtradas
                let html = `<table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Documento</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>IVA %</th>
                            <th>Total Línea</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>`;
                ventasData.forEach((row, i) => {
                    html += `<tr>
                        <td>${i + 1}</td>
                        <td>${row.name_client}</td>
                        <td>${row.document}</td>
                        <td>${row.code}</td>
                        <td>${row.name}</td>
                        <td>${row.category}</td>
                        <td>${row.qty}</td>
                        <td>${Number(row.price_unit).toFixed(2)}</td>
                        <td>${Number(row.iva_percent).toFixed(2)}</td>
                        <td>${Number(row.total_line).toFixed(2)}</td>
                        <td>${row.created_at}</td>
                    </tr>`;
                });
                html += `</tbody></table>`;

                // Codifica el HTML en base64 para máxima compatibilidad
                const uri = 'data:application/vnd.ms-excel;base64,';
                const base64 = window.btoa(unescape(encodeURIComponent(html)));
                const a = document.createElement('a');
                a.href = uri + base64;
                a.download = 'reporte_' + new Date().toISOString().slice(0, 10) + '.xls';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });

            // Exportar a PDF usando jsPDF y autoTable
           document.querySelector('.pdf-btn').addEventListener('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Construye una tabla temporal con todas las ventas filtradas
                let html = `<table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Documento</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>IVA %</th>
                            <th>Total Línea</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>`;
                ventasData.forEach((row, i) => {
                    html += `<tr>
                        <td>${i + 1}</td>
                        <td>${row.name_client}</td>
                        <td>${row.document}</td>
                        <td>${row.code}</td>
                        <td>${row.name}</td>
                        <td>${row.category}</td>
                        <td>${row.qty}</td>
                        <td>${Number(row.price_unit).toFixed(2)}</td>
                        <td>${Number(row.iva_percent).toFixed(2)}</td>
                        <td>${Number(row.total_line).toFixed(2)}</td>
                        <td>${row.created_at}</td>
                    </tr>`;
                });
                html += `</tbody></table>`;

                // Crea un div temporal para usarlo con autoTable
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                document.body.appendChild(tempDiv);

                doc.autoTable({ html: tempDiv.querySelector('table') });
                doc.save('reporte_' + new Date().toISOString().slice(0, 10) + '.pdf');

                document.body.removeChild(tempDiv);
            });
        document.addEventListener('DOMContentLoaded', function() {
            renderTable(currentPage);

            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable(currentPage);
                }
            });

            document.getElementById('nextPage').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable(currentPage);
                }
            });

            const allRows = Array.from(salesTableBody.getElementsByTagName('tr'));
            const searchFilterInput = document.getElementById('searchFilter');
            const filterButton = document.querySelector('.filter-btn');
            const salesTableBody = document.querySelector('tbody'); // Asegúrate que apunta al tbody correcto

            function filterTable() {
                const searchTerm = searchFilterInput.value.toLowerCase();
                const rows = salesTableBody.getElementsByTagName('tr');
                Array.from(rows).forEach(row => {
                    // Obtén los textos de las celdas relevantes
                    const clienteCell = row.cells[1]?.textContent.toLowerCase() || '';
                    const documentoCell = row.cells[2]?.textContent.toLowerCase() || '';
                    const codigoCell = row.cells[3]?.textContent.toLowerCase() || '';
                    const nombreProdCell = row.cells[4]?.textContent.toLowerCase() || '';
                    const fechaCellRaw = row.cells[10]?.textContent || '';
                    // Extrae solo la fecha (YYYY-MM-DD) si la celda tiene fecha y hora
                    const fechaCell = fechaCellRaw.split(' ')[0].toLowerCase();

                    // Busca en cliente, documento, código, nombre producto y fecha
                    const rowText = clienteCell + ' ' + documentoCell + ' ' + codigoCell + ' ' + nombreProdCell + ' ' + fechaCell;

                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            filterButton.addEventListener('click', filterTable);
            searchFilterInput.addEventListener('keyup', filterTable);

            
            
            document.getElementById('fechaFiltro').addEventListener('change', function() {
                window.location = '?fecha=' + this.value;
            });
           
        });
function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        showNotification('Cerrando sesión...', 'info');
        setTimeout(() => {
            window.location.href = '../login/logout.php';
        });
    }
}
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification-toast ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.classList.add('show');
    });
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        });
    });
}
    </script>
</body>
</html>