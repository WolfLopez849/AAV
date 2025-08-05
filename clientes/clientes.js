document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const appContainer = document.getElementById('appContainer');
    const formularioCliente = document.getElementById('formularioCliente');
    const btnAgregar = document.getElementById('btnAgregarCliente');
    const btnEditar = document.getElementById('btnEditar');
    const btnEliminar = document.getElementById('btnEliminar');
    const buscarInput = document.getElementById('buscarCliente');
    const cancelarBtn = document.getElementById('cancelarFormulario');
    const checkboxes = () => document.querySelectorAll('.checkCliente:checked');
    const numeroDocInput = document.getElementById('numero_doc');

    // 🔹 Leer variables de los mensajes del backend (si las envías por POST)
    const mensaje = document.body.dataset.mensaje;
    const tipo = document.body.dataset.tipo;

    // 🔹 Mostrar notificación Toast si hay mensaje
    if (mensaje && tipo) {
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: tipo,
            title: mensaje,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: tipo === 'success' ? '#28a745' : (tipo === 'error' ? '#dc3545' : '#17a2b8'),
            color: '#fff'
        });
    }

    // Solo números en numero_doc
    numeroDocInput?.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    // Alternar sidebar
    sidebarToggleBtn?.addEventListener('click', () => {
        appContainer.classList.toggle('collapsed');
    });

    // Buscar clientes
    buscarInput?.addEventListener('keyup', function () {
        const texto = this.value.toLowerCase();
        document.querySelectorAll("#tbodyClientes tr").forEach(fila => {
            const contenido = fila.textContent.toLowerCase();
            fila.style.display = contenido.includes(texto) ? "" : "none";
        });
    });

    // Mostrar formulario vacío (Agregar)
    btnAgregar?.addEventListener('click', function () {
        limpiarFormulario();
        mostrarFormulario('Datos del cliente', 'agregar');
    });

    // Botón cancelar (cierra con animación)
    cancelarBtn?.addEventListener('click', () => {
        ocultarFormulario();
    });

    // Checkbox selección
    document.addEventListener('change', () => {
        const seleccionados = checkboxes();
        btnEliminar.style.display = seleccionados.length > 0 ? 'inline-block' : 'none';
        btnEditar.style.display = seleccionados.length === 1 ? 'inline-block' : 'none';
    });

    // Botón editar
    btnEditar?.addEventListener('click', () => {
        const seleccionado = checkboxes()[0];
        if (!seleccionado) return;

        const fila = seleccionado.closest('tr');
        document.getElementById('clienteId').value = seleccionado.dataset.id;
        document.getElementById('nombre').value = fila.dataset.nombre;
        document.getElementById('tipo_doc').value = fila.dataset.tipo_doc;
        document.getElementById('numero_doc').value = fila.dataset.numero_doc;
        document.getElementById('telefono').value = fila.dataset.telefono;
        document.getElementById('correo').value = fila.dataset.correo;

        mostrarFormulario('Editar Cliente', 'editar');
    });

    // Botón eliminar (múltiple)
    btnEliminar?.addEventListener('click', () => {
        const seleccionados = checkboxes();
        if (seleccionados.length === 0) return;

        const ids = Array.from(seleccionados).map(chk => chk.dataset.id);

        Swal.fire({
            title: `¿Deseas eliminar ${ids.length} cliente(s)?`,
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'funcionalidades_clientes.php';

                const inputAccion = document.createElement('input');
                inputAccion.type = 'hidden';
                inputAccion.name = 'accion';
                inputAccion.value = 'eliminar';
                form.appendChild(inputAccion);

                ids.forEach(id => {
                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'ids[]';
                    inputId.value = id;
                    form.appendChild(inputId);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    /** Funciones auxiliares **/
    function limpiarFormulario() {
        document.getElementById('formCliente').reset();
        document.getElementById('clienteId').value = '';
    }

    function mostrarFormulario(titulo, accion) {
        document.getElementById('tituloFormulario').innerText = titulo;
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.value = accion;

        formularioCliente.classList.remove('ocultar');
        formularioCliente.classList.add('mostrar');
        formularioCliente.style.display = 'block';
    }

    function ocultarFormulario() {
        formularioCliente.classList.remove('mostrar');
        formularioCliente.classList.add('ocultar');

        setTimeout(() => {
            formularioCliente.style.display = 'none';
        }, 300);
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