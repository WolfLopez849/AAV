document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const appContainer = document.getElementById('appContainer');
    const formulario = document.getElementById('formularioProveedor');
    const btnAgregar = document.getElementById('btnAgregarProveedor');
    const btnEditar = document.getElementById('btnEditarProveedor');
    const btnEliminar = document.getElementById('btnEliminarProveedor');
    const buscarInput = document.getElementById('buscarProveedor');
    const cancelarBtn = document.getElementById('cancelarFormulario');
    const checkboxes = () => document.querySelectorAll('.checkProveedor:checked');
    const tituloFormulario = document.getElementById('tituloFormulario');
    const formProveedor = document.getElementById('formProveedor');
    const accionInput = document.getElementById('accionFormulario');


    // 🔹 Alternar sidebar
    sidebarToggleBtn?.addEventListener('click', () => {
        appContainer.classList.toggle('collapsed');
    });

    // 🔹 Búsqueda en tiempo real
    buscarInput?.addEventListener('keyup', function () {
        const texto = this.value.toLowerCase();
        document.querySelectorAll("#tbodyProveedores tr").forEach(fila => {
            const contenido = fila.textContent.toLowerCase();
            fila.style.display = contenido.includes(texto) ? "" : "none";
        });
    });

    // 🔹 Mostrar formulario para agregar
    btnAgregar?.addEventListener('click', function () {
        limpiarFormulario();
        mostrarFormulario('Registro de Proveedores', 'agregar');
    });

    // 🔹 Cancelar
    cancelarBtn?.addEventListener('click', () => {
        ocultarFormulario();
    });

    // 🔹 Detectar selección
    document.addEventListener('change', () => {
        const seleccionados = checkboxes();
        btnEliminar.style.display = seleccionados.length > 0 ? 'inline-block' : 'none';
        btnEditar.style.display = seleccionados.length === 1 ? 'inline-block' : 'none';
    });

    // 🔹 Editar proveedor
    btnEditar?.addEventListener('click', () => {
        const seleccionado = checkboxes()[0];
        if (!seleccionado) return;

        const fila = seleccionado.closest('tr');

        document.querySelector('input[name="id"]').value = seleccionado.dataset.id;
        document.querySelector('input[name="nombre"]').value = fila.dataset.nombre;
        document.querySelector('input[name="documento"]').value = fila.dataset.documento;
        document.querySelector('input[name="telefono"]').value = fila.dataset.telefono;
        document.querySelector('input[name="direccion"]').value = fila.dataset.direccion;
        document.querySelector('input[name="email"]').value = fila.dataset.email;

        mostrarFormulario('Editar Proveedor', 'editar');
    });

    // 🔹 Eliminar proveedor (múltiple)
    btnEliminar?.addEventListener('click', () => {
        const seleccionados = checkboxes();
        if (seleccionados.length === 0) return;

        const ids = Array.from(seleccionados).map(chk => chk.dataset.id);

        Swal.fire({
            title: `¿Deseas eliminar ${ids.length} proveedor(es)?`,
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
                form.action = 'funcionalidades_proveedores.php'; // 🔧 Acción definida correctamente

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

    // 🔹 Funciones auxiliares
    function limpiarFormulario() {
        formProveedor.reset();
        document.querySelector('input[name="id"]').value = '';
    }

    function mostrarFormulario(titulo, accion) {
        tituloFormulario.innerText = titulo;
        accionInput.value = accion; // <-- Este es el valor que irá al backend

        formulario.classList.remove('ocultar');
        formulario.classList.add('mostrar');
        formulario.style.display = 'block';
    }

    function ocultarFormulario() {
        formulario.classList.remove('mostrar');
        formulario.classList.add('ocultar');
        setTimeout(() => {
            formulario.style.display = 'none';
        }, 300);
    }
});

// 🔹 Mostrar notificación tipo toast si viene desde PHP
const mensaje = document.body.dataset.mensaje;
const tipo = document.body.dataset.tipo;

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
        color: '#333',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
}
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