// Espera a que todo el contenido del HTML se cargue antes de ejecutar el script
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM completamente cargado. main.js está ejecutándose.');

    // --- Elementos del DOM ---
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const appContainer = document.getElementById('appContainer');
    const datetimeElement = document.getElementById('datetime');
    const openRegisterBtn = document.getElementById('openRegisterBtn');
    const initialAmountInput = document.getElementById('initialAmount');

    // --- Funcionalidad del Sidebar (Menú lateral) ---
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
            appContainer.classList.toggle('collapsed');
        });
    } else {
        console.error('El botón con ID "sidebarToggleBtn" no fue encontrado.');
    }

    // --- Funcionalidad de Fecha y Hora ---
    // Actualiza la fecha y la hora cada segundo
    if (datetimeElement) {
        setInterval(() => {
            const now = new Date();
            datetimeElement.textContent = now.toLocaleString('es-CO', { dateStyle: 'long', timeStyle: 'short' });
        }, 1000);
    } else {
        console.error('El elemento con ID "datetime" no fue encontrado.');
    }

    // --- Funcionalidad de Apertura de Caja ---
    if (openRegisterBtn && initialAmountInput) {
        openRegisterBtn.addEventListener('click', function() {
            // Obtiene el valor del input y elimina espacios en blanco
            const amountValue = initialAmountInput.value.trim();
            // Convierte el valor a un número
            const amount = parseFloat(amountValue);

            // Validación: verifica que no esté vacío, que sea un número y que no sea negativo
            if (amountValue === '' || isNaN(amount) || amount < 0) {
                alert('Por favor, ingresa un monto con el que inicia la caja.');
                initialAmountInput.focus(); // Pone el cursor en el campo para corregir
                return; // Detiene la función si la validación falla
            }

            // Si la validación es exitosa, muestra una confirmación
            alert(`Caja abierta con un monto inicial de: $${amount.toFixed(2)}`);
            window.location.href = "../index.php";

            // Opcional: Limpiar el campo y deshabilitar el botón después de la apertura
            initialAmountInput.value = '0.00';
            openRegisterBtn.disabled = true;
            openRegisterBtn.textContent = 'Caja Abierta';
            openRegisterBtn.style.backgroundColor = '#566573'; // Color gris para indicar que está inactivo
            openRegisterBtn.style.cursor = 'not-allowed';
        });
    } else {
        console.error('No se encontró el botón "Abrir caja" o el campo de monto inicial.');
    }
});
function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        showNotification('Cerrando sesión...', 'info');
        setTimeout(() => {
            window.location.href = '../../login/logout.php';
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