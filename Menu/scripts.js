let notificationDropdownVisible = false;

let notifications = [];

function renderNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    const badge = document.getElementById('notificationBadge');
    if (!dropdown || !badge) return;
    dropdown.innerHTML = '';
    if (notifications.length === 0) {
        badge.style.display = 'none';
        dropdown.innerHTML = '<div class="notification-empty">No hay notificaciones</div>';
    } else {
        badge.style.display = 'flex';
        badge.textContent = notifications.length;
        notifications.forEach(n => {
            const item = document.createElement('div');
            item.className = 'notification-item';
            item.innerHTML = `<i class="fas ${n.icon}"></i><span>${n.text}</span>`;
            dropdown.appendChild(item);
        });
    }
}

function toggleNotifications(event) {
    event.stopPropagation();
    renderNotifications();
    const dropdown = document.getElementById('notificationDropdown');
    if (!dropdown) return;
    notificationDropdownVisible = !notificationDropdownVisible;
    if (notificationDropdownVisible) {
        dropdown.classList.add('show');
    } else {
        dropdown.classList.remove('show');
    }
}

document.addEventListener('click', function(event) {
    const notificationsArea = document.querySelector('.notifications');
    const dropdown = document.getElementById('notificationDropdown');
    if (!notificationsArea.contains(event.target)) {
        dropdown.classList.remove('show');
        notificationDropdownVisible = false;
    }
});

function navigateTo(section) {
    const routes = {
        'inventario': '../Inventario/inventario.php',
        'ventas': '../Ventas/ventas.php',
        'clientes': '../Clientes/clientes.php',
        'proveedores': '../Proveedores/proveedores.php',
        'usuarios': '../Usuarios/usuarios.php',
        'reportes': '../Reportes/reportes.php',
        'caja': '../Caja/caja.php',
        'configuracion': '../Configuracion/configuracion.php'
    };
    const route = routes[section];
    if (route) {
        showNotification(`Navegando a ${section}...`, 'info');
        setTimeout(() => {

            console.log(`Navegando a: ${route}`);
        }, 1000);
    } else {
        showNotification('Sección no disponible', 'error');
    }
}

function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        showNotification('Cerrando sesión...', 'info');
        setTimeout(() => {
            window.location.href = '../login/logout.php';
        }, 1000);
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
    }, 100);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

let topProductsChartInstance = null;
let topProductsDayChartInstance = null;
let yearlyEarningsChartInstance = null;
let salesByCategoryChartInstance = null;

async function fetchVentas() {
    const res = await fetch('Mocks/ventas.json');
    const data = await res.json();
    console.log('Ventas cargadas:', data);
    return data;
}

async function fetchProductos() {
    const res = await fetch('Mocks/productos.json');
    return await res.json();
}

function getCurrentMonth() {
    return new Date().getMonth() + 1;
}
function getCurrentYear() {
    return new Date().getFullYear();
}
function getCurrentDay() {
    return new Date().getDate();
}

function showNoDataMessage(chartId, message) {
    const container = document.getElementById(chartId).parentElement;
    let msg = container.querySelector('.no-data-message');
    if (!msg) {
        msg = document.createElement('div');
        msg.className = 'no-data-message';
        msg.style.position = 'absolute';
        msg.style.top = '50%';
        msg.style.left = '50%';
        msg.style.transform = 'translate(-50%, -50%)';
        msg.style.color = '#888';
        msg.style.fontSize = '1.1rem';
        msg.style.textAlign = 'center';
        msg.style.pointerEvents = 'none';
        container.appendChild(msg);
    }
    msg.textContent = message;
}
function hideNoDataMessage(chartId) {
    const container = document.getElementById(chartId).parentElement;
    const msg = container.querySelector('.no-data-message');
    if (msg) container.removeChild(msg);
}

function formatDateYYYYMMDD(date) {
    // Devuelve la fecha en formato YYYY-MM-DD usando la zona horaria local
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function isSameLocalDay(dateA, dateB) {
    return (
        dateA.getFullYear() === dateB.getFullYear() &&
        dateA.getMonth() === dateB.getMonth() &&
        dateA.getDate() === dateB.getDate()
    );
}

function parseLocalDate(str) {
    // str: "2025-07-15"
    const [year, month, day] = str.split('-').map(Number);
    return new Date(year, month - 1, day);
}

async function updateTopProductsDayChart() {
    const ventas = await fetchVentas();
    const vendidosPorProducto = {};
    const hoy = new Date();
    console.log('Hoy:', hoy);
    ventas.forEach(v => {
        const fechaVenta = parseLocalDate(v.fecha);
        console.log('Comparando', fechaVenta, hoy);
        if (isSameLocalDay(fechaVenta, hoy)) {
            console.log('Venta encontrada para hoy:', v);
            v.productos.forEach(p => {
                vendidosPorProducto[p.nombre] = (vendidosPorProducto[p.nombre] || 0) + p.cantidad;
            });
        }
    });
    const labels = Object.keys(vendidosPorProducto);
    const data = Object.values(vendidosPorProducto);
    if (topProductsDayChartInstance) {
        topProductsDayChartInstance.data.labels = labels;
        topProductsDayChartInstance.data.datasets[0].data = data;
        topProductsDayChartInstance.update();
        if (labels.length === 0) {
            showNoDataMessage('topProductsDayChart', 'No hay ventas para este día');
        } else {
            hideNoDataMessage('topProductsDayChart');
        }
    }
}

async function updateTopProductsChart() {
    const ventas = await fetchVentas();
    const vendidosPorProducto = {};
    const mesActual = getCurrentMonth();
    const yearActual = getCurrentYear();
    ventas.forEach(v => {
        const fecha = new Date(v.fecha);
        if ((fecha.getMonth() + 1) === mesActual && fecha.getFullYear() === yearActual) {
            v.productos.forEach(p => {
                vendidosPorProducto[p.nombre] = (vendidosPorProducto[p.nombre] || 0) + p.cantidad;
            });
        }
    });
    const labels = Object.keys(vendidosPorProducto);
    const data = Object.values(vendidosPorProducto);
    if (topProductsChartInstance) {
        topProductsChartInstance.data.labels = labels;
        topProductsChartInstance.data.datasets[0].data = data;
        topProductsChartInstance.update();
        if (labels.length === 0) {
            showNoDataMessage('topProductsChart', 'No hay ventas para este mes');
        } else {
            hideNoDataMessage('topProductsChart');
        }
    }
}

async function updateYearlyEarningsChart() {
    const ventas = await fetchVentas();
    const year = getCurrentYear();
    const gananciasPorMes = Array(12).fill(0);
    ventas.forEach(v => {
        const fecha = new Date(v.fecha);
        if (fecha.getFullYear() === year) {
            gananciasPorMes[fecha.getMonth()] += v.total;
        }
    });
    if (yearlyEarningsChartInstance) {
        yearlyEarningsChartInstance.data.datasets[0].data = gananciasPorMes;
        yearlyEarningsChartInstance.update();
        if (gananciasPorMes.reduce((a, b) => a + b, 0) === 0) {
            showNoDataMessage('yearlyEarningsChart', 'No hay ganancias para este año');
        } else {
            hideNoDataMessage('yearlyEarningsChart');
        }
    }
}

async function updateSalesByCategoryChart() {
    const ventas = await fetchVentas();
    const productos = await fetchProductos();
    // Mapear id a categoría
    const idToCategoria = {};
    productos.forEach(p => {
        idToCategoria[p.id] = p.categoria;
    });
    const vendidosPorCategoria = {};
    ventas.forEach(v => {
        v.productos.forEach(p => {
            const categoria = idToCategoria[p.id] || 'Otros';
            vendidosPorCategoria[categoria] = (vendidosPorCategoria[categoria] || 0) + p.cantidad;
        });
    });
    const labels = Object.keys(vendidosPorCategoria);
    const data = Object.values(vendidosPorCategoria);
    if (salesByCategoryChartInstance) {
        salesByCategoryChartInstance.data.labels = labels;
        salesByCategoryChartInstance.data.datasets[0].data = data;
        salesByCategoryChartInstance.update();
    }
}

function getChartColors(n) {
    const base = [
        'rgba(52, 152, 219, 0.8)',
        'rgba(41, 128, 185, 0.8)',
        'rgba(231, 76, 60, 0.8)',
        'rgba(46, 204, 113, 0.8)',
        'rgba(155, 89, 182, 0.8)',
        'rgba(241, 196, 15, 0.8)',
        'rgba(230, 126, 34, 0.8)',
        'rgba(26, 188, 156, 0.8)',
        'rgba(52, 73, 94, 0.8)',
        'rgba(127, 140, 141, 0.8)'
    ];
    return Array(n).fill(0).map((_, i) => base[i % base.length]);
}

function initializeCharts() {
    if (window.Chart) {
        const topProductsCtx = document.getElementById('topProductsChart');
        if (topProductsCtx) {
            topProductsChartInstance = new Chart(topProductsCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Unidades Vendidas',
                        data: [],
                        backgroundColor: getChartColors(10),
                        borderColor: getChartColors(10).map(c => c.replace('0.8', '1')),
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
        const topProductsDayCtx = document.getElementById('topProductsDayChart');
        if (topProductsDayCtx) {
            topProductsDayChartInstance = new Chart(topProductsDayCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: getChartColors(10),
                        borderColor: getChartColors(10).map(c => c.replace('0.8', '1')),
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
        const yearlyEarningsCtx = document.getElementById('yearlyEarningsChart');
        if (yearlyEarningsCtx) {
            yearlyEarningsChartInstance = new Chart(yearlyEarningsCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ganancias ($)',
                        data: Array(12).fill(0),
                        borderColor: 'rgba(52, 152, 219, 1)',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
        const salesByCategoryCtx = document.getElementById('salesByCategoryChart');
        if (salesByCategoryCtx) {
            salesByCategoryChartInstance = new Chart(salesByCategoryCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: getChartColors(10),
                        borderColor: getChartColors(10).map(c => c.replace('0.8', '1')),
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    }
}

async function updateAllCharts() {
    await updateTopProductsChart();
    await updateTopProductsDayChart();
    await updateYearlyEarningsChart();
    await updateSalesByCategoryChart();
}

function updateUserInfo() {
    const userName = document.getElementById('userName');
    const userRole = document.getElementById('userRole');
    if (userName) userName.textContent = 'Usuario';
    if (userRole) userRole.textContent = 'Administrador';
}

document.addEventListener('DOMContentLoaded', function() {
    updateUserInfo();
    initializeCharts();
    renderNotifications();
    setTimeout(updateAllCharts, 500); 
});

function updateRealTimeData() {
    setInterval(updateAllCharts, 30000);
}
updateRealTimeData();