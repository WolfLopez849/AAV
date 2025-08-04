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
    // las columnas se detectan por <thead>; si quisieras fijarlas:
    // columns: [
    //   null, null, null, null, null, null, null, null, null
    // ]
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
          { data : 'codigo'        },
          { data : 'nombre'        },
          { data : 'categoria'     },
          { data : 'precio_compra' }
        ],
        language : { url : 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' }
      });

      $('#searchTable tbody').on('click', 'tr', function () {
        const p = searchDT.row(this).data();
        $('#pCode').val(p.codigo);
        $('#pName').val(p.nombre);
        $('#pCategory').val(p.categoria);
        $('#pPrice').val(parseFloat(p.precio_compra).toFixed(2));
        $('#pQty').val('1');
        $('#pIVA').trigger('change');        // recalcula total
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

    if (table.column(0).data().toArray().includes(code)){
      alert('Ese producto ya fue agregado'); return;
    }

    const row = [
      code,
      $('#pName').val(),
      parseFloat($('#pPrice').val() || 0).toFixed(2),
      $('#pQty').val(),
      $('#pIVA').val(),
      $('#pDate').val() || '-',
      $('#pCategory').val() || '-',
      $('#pPaymentMethod').val(),
      $('#pTotal').val()
    ];

    table.row.add(row).draw(false);

    /* limpiar controles */
    this.reset();
    $('#pQty').val('1');
    $('#pIVA').val('18');
    $('#pTotal').val('');
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

    // Convierte las filas de la tabla en el formato que espera PHP
    const items = productos.map(row => ({
      product_id: row[0], // Asegúrate que sea el ID del producto, si no, ajusta
      price_unit: parseFloat(row[2]),
      iva_percent: parseFloat(row[4]),
      qty: parseInt(row[3]),
      total_line: parseFloat(row[8])
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
        window.location.href = 'index.html';
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
    window.location.href = 'cierre_caja_final/index.html';
  }
});

});

/* abrir_caja_final/main.js
   Al pulsar “Caja Abierta” redirige al módulo principal de caja
---------------------------------------------------------------*/
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('openCashBtn');
  btn.addEventListener('click', () => {
    // Ruta relativa: sube un nivel a /caja/ y carga index.html
    window.location.href = '../index.html';
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
