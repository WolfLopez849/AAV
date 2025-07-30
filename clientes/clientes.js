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

    // Ocultar mensajes después de 3 segundos
    setTimeout(() => {
        document.querySelectorAll('.alerta').forEach(alerta => alerta.remove());
    }, 3000);

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
        formularioCliente.style.display = 'block';
        document.getElementById('tituloFormulario').innerText = 'Datos del cliente';
        document.querySelector('button[type="submit"]').value = 'agregar';
    });

    // Botón cancelar
    cancelarBtn?.addEventListener('click', () => {
        formularioCliente.style.display = 'none';
        limpiarFormulario();
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

        document.getElementById('tituloFormulario').innerText = 'Editar Cliente';
        document.querySelector('button[type="submit"]').value = 'editar';

        formularioCliente.style.display = 'block';
    });

    // Botón eliminar (múltiple)
    btnEliminar?.addEventListener('click', () => {
        const seleccionados = checkboxes();
        if (seleccionados.length === 0) return;

        const ids = Array.from(seleccionados).map(chk => chk.dataset.id);

        if (confirm(`¿Deseas eliminar ${ids.length} cliente(s)? Esta acción no se puede deshacer.`)) {
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

    // Función para limpiar formulario
    function limpiarFormulario() {
        document.getElementById('formCliente').reset();
        document.getElementById('clienteId').value = '';
    }
});
