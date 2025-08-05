// main.js
document.addEventListener('DOMContentLoaded', () => {

  /* ╔══════════════════════════════════════════════╗
     ║   1. Sidebar plegable                        ║
     ╚══════════════════════════════════════════════╝ */
  const app = document.getElementById('appContainer');
  document.getElementById('sidebarToggleBtn')
          .addEventListener('click', () => app.classList.toggle('collapsed'));

  /* ╔══════════════════════════════════════════════╗
     ║   2. DataTable principal                     ║
     ╚══════════════════════════════════════════════╝ */
  const table = $('#productTable').DataTable({
  responsive : true,
  pageLength : 10,
  language   : { url : 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' },
  columnDefs: [
    { targets: 0, visible: false } // Oculta la columna del ID
  ]
});

  /* ╔══════════════════════════════════════════════╗
     ║   3. Botón NUEVO CLIENTE                     ║
     ╚══════════════════════════════════════════════╝ */
  $('#newClientBtn').on('click', () => {
    $('#pNameProd,#pDocNum,#pEmail,#pTel').val('');
    $('#pDocType').val('');
  });

  /* ╔══════════════════════════════════════════════╗
     ║   4. Modal de búsqueda de producto           ║
     ╚══════════════════════════════════════════════╝ */
  const modal  = new bootstrap.Modal('#productModal');
  let searchDT = null;

  $('#searchProductBtn').on('click', () => {
    modal.show();
    loadProductList();
  });

  function loadProductList () {
    $.ajax({
      url      : 'search_products.php',
      dataType : 'json'
    })
    .done(data => {
      if (searchDT) {
        searchDT.clear().rows.add(data).draw(false);
        return;
      }
      searchDT = $('#searchTable').DataTable({
        data     : data,
        columns  : [
          { data: 'id', visible: false }, // ID oculto
          { data: 'codigo' },
          { data: 'nombre' },
          { data: 'categoria' },
          { data: 'precio_compra' },
          { data: 'iva' }
        ],
        language : { url : 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' }
      });

      $('#searchTable tbody').on('click', 'tr', function () {
        const p = searchDT.row(this).data();
        $('#pId').val(p.id); // <-- ID real
        $('#pCode').val(p.codigo);
        $('#pName').val(p.nombre);
        $('#pCategory').val(p.categoria);
        $('#pPrice').val(parseFloat(p.precio_compra).toFixed(2));
        $('#pQty').val('1');
        $('#pIVA').val(p.iva);
        $('#pTotal').val((parseFloat($('#pPrice').val()) * parseInt($('#pQty').val()) * (1 + parseFloat($('#pIVA').val()) / 100)).toFixed(2));
        modal.hide();
      });
    })
    .fail((jq, status, err) => {
      console.error('AJAX error:', status, err, jq.responseText);
      alert('⚠️ Error al cargar productos\nRevisa consola para más detalle.');
    });
  }

  /* ╔══════════════════════════════════════════════╗
     ║   5. Cálculo dinámico del TOTAL              ║
     ╚══════════════════════════════════════════════╝ */
  $('#productForm').on('input', '#pPrice,#pIVA,#pQty', () => {
    const price = parseFloat($('#pPrice').val()) || 0;
    const iva   = parseFloat($('#pIVA').val())   || 0;
    const qty   = parseInt($('#pQty').val())     || 0;

    const sub   = price * qty;
    $('#pTotal').val((sub + sub * iva / 100).toFixed(2));
  });

  /* ╔══════════════════════════════════════════════╗
     ║   6. Añadir producto a la tabla              ║
     ╚══════════════════════════════════════════════╝ */
  $('#productForm').on('submit', function (e) {
  e.preventDefault();

  const code = $('#pCode').val().trim();
  if (!code){ alert('Selecciona un producto primero'); return; }

  // Evita duplicados por código
  if (table.column(1).data().toArray().includes(code)){
    alert('Ese producto ya fue agregado'); return;
  }

  const row = [
    $('#pId').val(),             // ID real (oculto)
    $('#pCode').val(),           // Código (visible)
    $('#pName').val(),           // Nombre (visible)
    parseFloat($('#pPrice').val() || 0).toFixed(2),
    $('#pQty').val(),
    $('#pIVA').val(),
    $('#pDate').val() || '-',
    $('#pCategory').val() || '-',
    $('#pPaymentMethod').val(),
    $('#pTotal').val()
  ];
  table.row.add(row).draw(false);
});

  /* ╔══════════════════════════════════════════════╗
     ║   7. Seleccionar fila y eliminar producto    ║
     ╚══════════════════════════════════════════════╝ */
  let selectedRow = null;
  const $delete = $('#deleteRowBtn').prop('disabled', true);

  $('#productTable tbody').on('click', 'tr', function () {
    if ($(this).hasClass('dt-selected')) {
      $(this).removeClass('dt-selected');
      selectedRow = null;
      $delete.prop('disabled', true);
    } else {
      $('#productTable tbody tr.dt-selected').removeClass('dt-selected');
      $(this).addClass('dt-selected');
      selectedRow = table.row(this);
      $delete.prop('disabled', false);
    }
  });

  $delete.on('click', () => {
    if (!selectedRow) return;
    const d   = selectedRow.data();
    const msg = `¿Eliminar “${d[1]}” (cód. ${d[0]})?`;
    if (confirm(msg)) {
      selectedRow.remove().draw(false);
      selectedRow = null;
      $delete.prop('disabled', true);
    }
  });

  /* ╔══════════════════════════════════════════════╗
     ║   8. Registrar venta (placeholder)           ║
     ╚══════════════════════════════════════════════╝ */
  $('#registerSaleBtn').on('click', () => {
  const productos = table.rows().data().toArray();
  if (productos.length === 0) {
    alert('Agrega al menos un producto para registrar la venta.');
    return;
  }

  // Datos del cliente
  const customer = {
    name: $('#pNameProd').val(),
    doc_type: $('#pDocType').val(),
    doc_num: $('#pDocNum').val(),
    email: $('#pEmail').val(),
    phone: $('#pTel').val()
  };

  // AJUSTA AQUÍ: Asegúrate que el ID real esté en la posición correcta
  const items = productos.map(row => ({
    product_id: row[0], // <-- Debe ser el ID real, no el código
    code: row[1],
    name: row[2],
    price_unit: parseFloat(row[3]),
    qty: parseInt(row[4]),
    iva_percent: parseFloat(row[5]),
    category: row[7],
    total_line: parseFloat(row[9])
  }));

  // Calcula el total de la venta
  const total = items.reduce((sum, it) => sum + it.total_line, 0);

  const venta = {
    customer: customer,
    items: items,
    total: total
  };

  $.ajax({
      url: 'save_sale.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(venta),
      success: function(res) {
        alert('Venta registrada correctamente.');
        // Limpiar datos del cliente
        $('#pNameProd, #pDocType, #pDocNum, #pEmail, #pTel').val('');
        window.location.href = 'index.php';
      },
      error: function(xhr, err) {
        alert('Error al registrar la venta.');
        console.error(err, xhr.responseText);
      }
    });
  });

  /* ╔══════════════════════════════════════════════╗
     ║   9. Cierre de caja                          ║
     ╚══════════════════════════════════════════════╝ */
  $('#closeCashBtn').on('click', () => {
  if (confirm('¿Estás seguro de cerrar caja?')) {
    // Redirige al formulario de apertura
    window.location.href = 'abrir_caja_final/index.php';
  }
});

});

/* abrir_caja_final/main.js
   Al pulsar “Caja Abierta” redirige al módulo principal de caja
---------------------------------------------------------------*/
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('openCashBtn');
  btn.addEventListener('click', () => {
    // Ruta relativa: sube un nivel a /caja/ y carga index.php
    window.location.href = '../index.php';
  });
});

document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('pDate');
    if (dateInput) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        dateInput.value = `${yyyy}-${mm}-${dd}`;
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