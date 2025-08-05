// JavaScript para alternar la visibilidad de la barra lateral
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM completamente cargado. main.js está ejecutándose.'); // Mensaje de depuración

    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const appContainer = document.getElementById('appContainer');

    // Asegúrate de que el botón existe antes de añadir el event listener
    if (sidebarToggleBtn) {
        console.log('Botón de alternancia encontrado en main.js.'); // Mensaje de depuración
        sidebarToggleBtn.addEventListener('click', function() {
            console.log('Botón de alternancia clicado en main.js.'); // Mensaje de depuración
            appContainer.classList.toggle('collapsed');
            // La visibilidad del texto del logo y los elementos de navegación se maneja con CSS
            // a través de la clase 'collapsed' y las transiciones de opacidad y ancho.
        });
    } else {
        console.error('El botón con ID "sidebarToggleBtn" no fue encontrado en main.js. Asegúrate de que el ID es correcto y el elemento existe en el HTML.');
    }
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