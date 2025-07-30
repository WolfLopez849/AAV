let productos = JSON.parse(localStorage.getItem('productos')) || [];
let editando = false;
let seleccionados = new Set();

document.addEventListener('DOMContentLoaded', () => {
  actualizarTabla();
  document.getElementById('formProducto').addEventListener('submit', guardarProducto);
  document.getElementById('buscarProducto').addEventListener('input', actualizarTabla);
  document.getElementById('btnMostrarFormulario').addEventListener('click', toggleFormulario);
  document.getElementById('btnEditarSeleccionados').addEventListener('click', editarSeleccionado);

  const appContainer = document.getElementById('appContainer');
  const toggleBtn = document.getElementById('sidebarToggleBtn');
  toggleBtn?.addEventListener('click', () => {
    appContainer.classList.toggle('collapsed');
  });
});

function toggleFormulario() {
  const contenedor = document.getElementById('contenedorFormulario');
  const btn = document.getElementById('btnMostrarFormulario');
  const visible = contenedor.classList.contains('visible');

  if (visible) {
    if (editando) {
      document.querySelector('.contenedor-tabla')?.classList.remove('oculto');
      document.querySelector('.buscador-contenedor, .buscador-pastilla')?.classList.remove('oculto');
    }
    contenedor.classList.remove('visible');
    contenedor.classList.add('oculto');
    btn.classList.remove('cancelar');
    btn.innerHTML = '<i class="fas fa-plus-circle"></i> Agregar Producto';
    editando = false;
    seleccionados.clear();
    actualizarTabla();
  } else {
    resetFormulario();
    contenedor.classList.remove('oculto');
    contenedor.classList.add('visible');
    btn.classList.add('cancelar');
    btn.innerHTML = '<i class="fas fa-times"></i> Cancelar';
  }
}

function guardarProducto(e) {
  e.preventDefault();
  const form = e.target;
  const data = new FormData(form);
  const nuevo = Object.fromEntries(data.entries());

  nuevo.precioCompra = parseFloat(nuevo.precioCompra);
  nuevo.precioVenta = parseFloat(nuevo.precioVenta);
  nuevo.stock = parseInt(nuevo.stock);
  nuevo.iva = parseInt(nuevo.iva);
  nuevo.codigo = nuevo.codigo.trim();
  nuevo.id = nuevo.codigo;

  const existente = productos.find(p => p.codigo === nuevo.codigo);

  if (editando && existente) {
    Object.assign(existente, nuevo);
    showNotification(`Producto actualizado: ${nuevo.nombre}`, 'success');
  } else if (!existente) {
    productos.push(nuevo);
    showNotification(`Producto agregado: ${nuevo.nombre}`, 'success');
  } else {
    showNotification(`Ya existe un producto con ese código`, 'error');
    return;
  }

  localStorage.setItem('productos', JSON.stringify(productos));

  editando = false;
  form.reset();
  document.getElementById('contenedorFormulario').classList.remove('visible');
  document.getElementById('contenedorFormulario').classList.add('oculto');
  document.getElementById('btnMostrarFormulario').innerHTML = '<i class="fas fa-plus-circle"></i> Agregar Producto';
  seleccionados.clear();
  actualizarTabla();
}

function actualizarTabla() {
  const tbody = document.getElementById('listaProductos');
  const filtro = document.getElementById('buscarProducto')?.value.toLowerCase() || '';
  const mensaje = document.getElementById('mensajeVacio');
  const textoMensaje = document.getElementById('textoMensajeVacio');
  const tabla = document.querySelector('.contenedor-tabla');
  const buscador = document.querySelector('.buscador-contenedor, .buscador-pastilla');

  tbody.innerHTML = '';

  if (productos.length === 0) {
    if (mensaje && textoMensaje) {
      textoMensaje.innerHTML = 'Aún no tienes productos agregados. Pulsa en <strong>Agregar Producto</strong> para comenzar.';
      mensaje.classList.remove('oculto');
    }
    buscador?.classList.add('oculto');
    tabla?.classList.add('oculto');
    actualizarBarraOpciones();
    return;
  }

  buscador?.classList.remove('oculto');

  const filtrados = productos.filter(p =>
    p.nombre.toLowerCase().includes(filtro) || p.codigo.toLowerCase().includes(filtro)
  );

  if (filtrados.length === 0) {
    if (mensaje && textoMensaje) {
      textoMensaje.innerHTML = 'No se encontraron productos con ese término.';
      mensaje.classList.remove('oculto');
    }
    tabla?.classList.add('oculto');
    actualizarBarraOpciones();
  } else {
    mensaje?.classList.add('oculto');
    tabla?.classList.remove('oculto');

    filtrados.forEach(p => {
      const tr = document.createElement('tr');
      if (p.stock <= 5) tr.classList.add('alerta-stock');
      tr.setAttribute('data-codigo', p.codigo);
      tr.classList.toggle('seleccionado', seleccionados.has(p.codigo));

      tr.innerHTML = `
        <td><input type="checkbox" class="checkbox-seleccion" data-codigo="${p.codigo}" ${seleccionados.has(p.codigo) ? 'checked' : ''}></td>
        <td>${p.nombre}</td>
        <td>${p.codigo}</td>
        <td>$${p.precioVenta.toFixed(2)}</td>
        <td>${p.stock}</td>
        <td>${p.categoria || '-'}</td>
        <td>${p.proveedor || '-'}</td>
      `;

      tr.addEventListener('click', (e) => {
        if (e.target.tagName !== 'INPUT' && !editando) {
          const codigo = p.codigo;
          if (e.ctrlKey) {
            seleccionados.has(codigo) ? seleccionados.delete(codigo) : seleccionados.add(codigo);
          } else {
            seleccionados.clear();
            seleccionados.add(codigo);
          }
          actualizarTabla();
        }
      });

      tbody.appendChild(tr);
    });
  }

  document.querySelectorAll('.checkbox-seleccion').forEach(checkbox => {
    checkbox.addEventListener('change', (e) => {
      if (editando) {
        e.preventDefault();
        e.target.checked = seleccionados.has(e.target.dataset.codigo);
        return;
      }
      const codigo = e.target.dataset.codigo;
      if (e.target.checked) {
        seleccionados.add(codigo);
      } else {
        seleccionados.delete(codigo);
      }
      actualizarBarraOpciones();
    });
  });

  actualizarBarraOpciones();
}

function actualizarBarraOpciones() {
  const barra = document.querySelector('.barra-opciones');
  const btnEditar = document.getElementById('btnEditarSeleccionados');
  if (!barra || !btnEditar) return;

  barra.classList.toggle('visible', seleccionados.size > 0);
  btnEditar.style.display = (!editando && seleccionados.size === 1) ? 'inline-flex' : 'none';
}

function editarSeleccionado() {
  if (seleccionados.size !== 1) return;
  const codigo = [...seleccionados][0];
  const producto = productos.find(p => p.codigo === codigo);
  if (!producto) return;

  mostrarFormularioConProducto(producto);
  showNotification(`Editando: ${producto.nombre}`, 'info');
}

function eliminarSeleccionados() {
  const confirmacion = confirm("¿Eliminar productos seleccionados?");
  if (!confirmacion) return;
  productos = productos.filter(p => !seleccionados.has(p.codigo));
  seleccionados.clear();
  localStorage.setItem('productos', JSON.stringify(productos));
  actualizarTabla();
  showNotification(`Productos eliminados`, 'success');
}

function mostrarFormularioConProducto(producto) {
  const form = document.getElementById('formProducto');
  resetFormulario();
  Object.keys(producto).forEach(k => {
    if (k === 'id') return;
    if (form.elements[k]) form.elements[k].value = producto[k];
  });

  document.querySelector('.contenedor-tabla')?.classList.add('oculto');
  document.querySelector('.buscador-contenedor, .buscador-pastilla')?.classList.add('oculto');
  editando = true;
  document.getElementById('contenedorFormulario').classList.remove('oculto');
  document.getElementById('contenedorFormulario').classList.add('visible');
  document.getElementById('btnMostrarFormulario').innerHTML = '<i class="fas fa-times"></i> Cancelar';
  actualizarBarraOpciones();
}

function resetFormulario() {
  document.getElementById('formProducto').reset();
  editando = false;
}

function showNotification(mensaje, tipo = 'info') {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.textContent = mensaje;
  toast.className = `toast show ${tipo}`;
  setTimeout(() => toast.className = 'toast', 3000);
}
