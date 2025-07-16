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
        'inventario': '../Inventario/inventario.html',
        'ventas': '../Ventas/ventas.html',
        'clientes': '../Clientes/clientes.html',
        'proveedores': '../Proveedores/proveedores.html',
        'usuarios': '../Usuarios/usuarios.html',
        'reportes': '../Reportes/reportes.html',
        'caja': '../Caja/caja.html',
        'configuracion': '../Configuracion/configuracion.html'
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
            window.location.href = '?action=logout';
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

function initializeCharts() {
    if (window.Chart) {
        const topProductsCtx = document.getElementById('topProductsChart');
        if (topProductsCtx) {
            new Chart(topProductsCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Producto A', 'Producto B', 'Producto C', 'Producto D', 'Producto E'],
                    datasets: [{
                        label: 'Unidades Vendidas',
                        data: [120, 95, 87, 76, 65],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.8)',
                            'rgba(41, 128, 185, 0.8)',
                            'rgba(231, 76, 60, 0.8)',
                            'rgba(46, 204, 113, 0.8)',
                            'rgba(155, 89, 182, 0.8)'
                        ],
                        borderColor: [
                            'rgba(52, 152, 219, 1)',
                            'rgba(41, 128, 185, 1)',
                            'rgba(231, 76, 60, 1)',
                            'rgba(46, 204, 113, 1)',
                            'rgba(155, 89, 182, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
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
            new Chart(topProductsDayCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Producto X', 'Producto Y', 'Producto Z', 'Producto W'],
                    datasets: [{
                        data: [25, 20, 15, 10],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.8)',
                            'rgba(41, 128, 185, 0.8)',
                            'rgba(231, 76, 60, 0.8)',
                            'rgba(46, 204, 113, 0.8)'
                        ],
                        borderColor: [
                            'rgba(52, 152, 219, 1)',
                            'rgba(41, 128, 185, 1)',
                            'rgba(231, 76, 60, 1)',
                            'rgba(46, 204, 113, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        const yearlyEarningsCtx = document.getElementById('yearlyEarningsChart');
        if (yearlyEarningsCtx) {
            new Chart(yearlyEarningsCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ganancias ($)',
                        data: [15000, 18000, 22000, 19000, 25000, 28000, 32000, 30000, 35000, 38000, 42000, 45000],
                        borderColor: 'rgba(52, 152, 219, 1)',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
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
            new Chart(salesByCategoryCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Electrónicos', 'Ropa', 'Alimentos', 'Hogar', 'Otros'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.8)',
                            'rgba(41, 128, 185, 0.8)',
                            'rgba(231, 76, 60, 0.8)',
                            'rgba(46, 204, 113, 0.8)',
                            'rgba(155, 89, 182, 0.8)'
                        ],
                        borderColor: [
                            'rgba(52, 152, 219, 1)',
                            'rgba(41, 128, 185, 1)',
                            'rgba(231, 76, 60, 1)',
                            'rgba(46, 204, 113, 1)',
                            'rgba(155, 89, 182, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,                    
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    }
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
});

function updateRealTimeData() {
    setInterval(() => {
    }, 30000);
}
updateRealTimeData(); 