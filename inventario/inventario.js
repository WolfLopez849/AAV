let productos = [];
let editando = false;
let seleccionados = new Set();

document.addEventListener('DOMContentLoaded', () => {
  cargarProductos();
  document.getElementById('formProducto').addEventListener('submit', guardarProducto);
  document.getElementById('buscarProducto').addEventListener('input', actualizarTabla);
  document.getElementById('btnMostrarFormulario').addEventListener('click', toggleFormulario);
  document.getElementById('btnEditarSeleccionados').addEventListener('click', editarSeleccionado);

  const toggleBtn = document.getElementById('sidebarToggleBtn');
  toggleBtn?.addEventListener('click', () => {
    document.getElementById('appContainer').classList.toggle('collapsed');
  });

  // Formato en tiempo real para precios
  const precioCompra = document.querySelector('input[name="precioCompra"]');
  const precioVenta = document.querySelector('input[name="precioVenta"]');

  [precioCompra, precioVenta].forEach(input => {
    input.addEventListener('input', (e) => {
      let val = e.target.value.replace(/\D/g, '');
      if (val) {
        e.target.setAttribute('data-raw', val);
        e.target.value = formatCOP(val);
      } else {
        e.target.value = '';
        e.target.removeAttribute('data-raw');
      }
    });

    // Evita ingresar puntos o comas manuales
    input.addEventListener('keydown', e => {
      if (e.key === '.' || e.key === ',' || e.key === 'e') {
        e.preventDefault();
      }
    });
  });
});

function formatCOP(valor) {
  return '$ ' + parseInt(valor, 10).toLocaleString('es-CO');
}

function toggleFormulario() {
  const contenedor = document.getElementById('contenedorFormulario');
  const btn = document.getElementById('btnMostrarFormulario');
  const visible = contenedor.classList.contains('visible');

  if (visible) {
    if (editando) {
      document.querySelector('.contenedor-tabla')?.classList.remove('oculto');
      document.querySelector('.contenedor-busqueda-acciones')?.classList.remove('oculto');
    }
    contenedor.classList.remove('visible');
    contenedor.classList.add('oculto');
    btn.classList.remove('cancelar');
    btn.innerHTML = '<i class="fas fa-plus-circle"></i> Agregar Producto';
    editando = false;
    seleccionados.clear();
    cargarProductos();
  } else {
    resetFormulario();
    contenedor.classList.remove('oculto');
    contenedor.classList.add('visible');
    btn.classList.add('cancelar');
    btn.innerHTML = '<i class="fas fa-times"></i> Cancelar';
  }
}

async function cargarProductos() {
  try {
    const res = await fetch('api.php');
    productos = await res.json();
    actualizarTabla();
  } catch (error) {
    showNotification('Error al cargar productos', 'error');
  }
}

async function guardarProducto(e) {
  e.preventDefault();
  const form = e.target;
  const data = Object.fromEntries(new FormData(form));

  // Obtener valores sin formato
  data.precioCompra = parseFloat(form.querySelector('input[name="precioCompra"]').getAttribute('data-raw') || 0);
  data.precioVenta = parseFloat(form.querySelector('input[name="precioVenta"]').getAttribute('data-raw') || 0);
  data.stock = parseInt(data.stock);
  data.iva = parseInt(data.iva);

  if (!data.nombre || !data.codigo || !data.precioCompra || !data.precioVenta || !data.stock) {
    showNotification('Por favor completa todos los campos obligatorios', 'error');
    return;
  }

  if (data.precioVenta <= data.precioCompra) {
    showNotification('El precio de venta no puede ser menor al precio de compra.', 'error');
    return;
  }

  // Validar código único
  const codigoDuplicado = productos.some(p =>
    p.codigo === data.codigo && p.id !== data.id
  );
  if (codigoDuplicado) {
    showNotification('Ya existe un producto con ese código', 'error');
    return;
  }

  const metodo = data.id ? 'PUT' : 'POST';

  try {
    await fetch('api.php', {
      method: metodo,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    showNotification(data.id ? 'Producto actualizado' : 'Producto agregado', 'success');
    resetFormulario();
    toggleFormulario();
    cargarProductos();
  } catch (error) {
    showNotification('Error al guardar producto', 'error');
  }
}

function actualizarTabla() {
  const tbody = document.getElementById('listaProductos');
  const filtro = document.getElementById('buscarProducto')?.value.toLowerCase() || '';
  const mensaje = document.getElementById('mensajeVacio');
  const textoMensaje = document.getElementById('textoMensajeVacio');
  const tabla = document.querySelector('.contenedor-tabla');
  const contenedorBuscador = document.querySelector('.contenedor-busqueda-acciones');

  tbody.innerHTML = '';

  if (productos.length === 0) {
    textoMensaje.innerHTML = 'Aún no tienes productos agregados. Pulsa en <strong>Agregar Producto</strong> para comenzar.';
    mensaje.classList.remove('oculto');
    tabla?.classList.add('oculto');
    contenedorBuscador?.classList.add('oculto');
    actualizarBarraOpciones();
    return;
  }

  const filtrados = productos.filter(p =>
    p.nombre.toLowerCase().includes(filtro) || p.codigo.toLowerCase().includes(filtro)
  );

  if (filtrados.length === 0) {
    textoMensaje.innerHTML = 'No se encontraron productos con ese término.';
    mensaje.classList.remove('oculto');
    tabla?.classList.add('oculto');
  } else {
    mensaje?.classList.add('oculto');
    tabla?.classList.remove('oculto');
  }

  contenedorBuscador?.classList.remove('oculto');

  filtrados.forEach(p => {
    const tr = document.createElement('tr');
    tr.setAttribute('data-id', p.id);
    tr.classList.toggle('seleccionado', seleccionados.has(p.id));
    if (p.stock <= 5) tr.classList.add('alerta-stock');

    const columnas = [
      `<input type="checkbox" class="checkbox-seleccion" data-id="${p.id}" ${seleccionados.has(p.id) ? 'checked' : ''}>`,
      p.nombre,
      p.codigo,
      formatCOP(p.precioVenta),
      p.stock <= 5
        ? `<i class="fas fa-exclamation-triangle icono-alerta" title="Stock bajo: considera reabastecer"></i> ${p.stock}`
        : p.stock,
      p.categoria || '-',
      p.proveedor || '-'
    ];

    columnas.forEach(col => {
      const td = document.createElement('td');
      td.innerHTML = col;
      td.classList.add('celda-animada');
      tr.appendChild(td);
    });

    const checkbox = tr.querySelector('.checkbox-seleccion');

    function actualizarSeleccion(id, activo) {
      if (activo) {
        seleccionados.add(id);
      } else {
        seleccionados.delete(id);
      }
      tr.classList.toggle('seleccionado', activo);
      if (checkbox) checkbox.checked = activo;
      actualizarBarraOpciones();
    }

    // Clic en fila
    tr.addEventListener('click', e => {
      if (e.target.tagName !== 'INPUT' && !editando) {
        if (e.ctrlKey) {
          actualizarSeleccion(p.id, !seleccionados.has(p.id));
        } else {
          seleccionados.clear();
          seleccionados.add(p.id);
          actualizarTabla();
        }
      }
    });

    // Clic en checkbox
    if (checkbox) {
      checkbox.addEventListener('change', e => {
        actualizarSeleccion(p.id, e.target.checked);
      });
    }

    tbody.appendChild(tr);
  });

  actualizarBarraOpciones();
}

function actualizarBarraOpciones() {
  const barra = document.querySelector('.botones-busqueda');
  const btnEditar = document.getElementById('btnEditarSeleccionados');
  const btnEliminar = document.getElementById('btnEliminarSeleccionados');
  const count = seleccionados.size;

  if (count > 0) {
    barra.classList.add('visible');
    btnEditar.style.display = count === 1 ? 'inline-flex' : 'none';
    btnEliminar.style.display = 'inline-flex';
  } else {
    barra.classList.remove('visible');
    btnEditar.style.display = 'none';
    btnEliminar.style.display = 'none';
  }
}

function editarSeleccionado() {
  if (seleccionados.size !== 1) return;

  const id = [...seleccionados][0];
  const producto = productos.find(p => p.id == id);
  if (!producto) return;

  const form = document.getElementById('formProducto');
  resetFormulario();

  for (let campo in producto) {
    if (form.elements[campo]) {
      if (campo === 'precioCompra' || campo === 'precioVenta') {
        const val = producto[campo];
        form.elements[campo].setAttribute('data-raw', val);
        form.elements[campo].value = formatCOP(val);
      } else {
        form.elements[campo].value = producto[campo];
      }
    }
  }

  const contenedorFormulario = document.getElementById('contenedorFormulario');
  contenedorFormulario.classList.remove('oculto');
  contenedorFormulario.classList.add('visible');

  document.querySelector('.contenedor-tabla')?.classList.add('oculto');
  document.querySelector('.contenedor-busqueda-acciones')?.classList.add('oculto');

  const btn = document.getElementById('btnMostrarFormulario');
  btn.classList.add('cancelar');
  btn.innerHTML = '<i class="fas fa-times"></i> Cancelar';

  editando = true;
  actualizarBarraOpciones();
}

async function eliminarSeleccionados() {
  const confirmacion = confirm("¿Eliminar productos seleccionados?");
  if (!confirmacion) return;

  try {
    for (let id of seleccionados) {
      await fetch('api.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      });
    }
    showNotification("Productos eliminados", 'success');
    seleccionados.clear();
    cargarProductos();
  } catch (error) {
    showNotification("Error al eliminar", 'error');
  }
}

function resetFormulario() {
  const form = document.getElementById('formProducto');
  form.reset();
  form.elements['id'].value = '';
  form.querySelectorAll('input[data-raw]').forEach(el => el.removeAttribute('data-raw'));
  editando = false;
}

function showNotification(msg, tipo = 'info') {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = msg;
  toast.className = `toast show ${tipo}`;
  setTimeout(() => (toast.className = 'toast'), 3000);
}
document.addEventListener('DOMContentLoaded', () => {
  fetch('buscar_proveedor.php')
    .then(res => res.json())
    .then(proveedores => {
      const select = document.getElementById('proveedorSelect');
      if (!select) return;
      proveedores.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.nombre;
        opt.textContent = p.nombre;
        select.appendChild(opt);
      });
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