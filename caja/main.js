document.addEventListener('DOMContentLoaded', () => {
  /* --- Sidebar toggle --- */
  const app = document.getElementById('appContainer');
  document.getElementById('sidebarToggleBtn').addEventListener('click', () => {
    app.classList.toggle('collapsed');
  });

  /* --- DataTable --- */
  const table = $('#productTable').DataTable({
    responsive: true,
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' }
  });

  /* --- Form logic --- */
  $('#productForm').on('input', '#pPrice, #pIVA', () => {
    const price = parseFloat($('#pPrice').val()) || 0;
    const iva   = parseFloat($('#pIVA').val())   || 0;
    $('#pTotal').val((price + price * iva / 100).toFixed(2));
  });

  $('#productForm').on('submit', function (e) {
    e.preventDefault();
    const code = $('#pCode').val().trim();

    if (table.column(0).data().toArray().includes(code)) {
      alert('Ese código ya existe');
      return;
    }

    table.row.add([
      code,
      $('#pName').val()     || '-',
      parseFloat($('#pPrice').val()).toFixed(2),
      parseFloat($('#pIVA').val()).toFixed(2),
      $('#pDate').val()     || '-',
      $('#pCategory').val() || '-',
      $('#pTotal').val()    || '0.00'
    ]).draw(false);

    this.reset();
    $('#pCode').focus();
  });

  /* Botón “Registrar Venta” */
  $('.actions-row .btn-primary').on('click', enviarVenta);
});

/* -------- Función principal -------- */
async function enviarVenta() {
  const items = $('#productTable').DataTable().rows().data().toArray().map(r => ({
    product_id  : r[0],
    price_unit  : parseFloat(r[2]),
    iva_percent : parseFloat(r[3]),
    qty         : 1,
    total_line  : parseFloat(r[6])
  }));

  const payload = {
    customer: {
      name     : $('#pName').val(),
      doc_type : $('#pDocType').val(),
      doc_num  : $('#pDocNum').val(),
      email    : $('#pEmail').val(),
      phone    : $('#pTel').val()
      // dirección eliminada
    },
    items : items,
    total : items.reduce((acc, i) => acc + i.total_line, 0)
  };

  const res  = await fetch('save_sale.php', {
    method  : 'POST',
    headers : { 'Content-Type': 'application/json' },
    body    : JSON.stringify(payload)
  });
  const data = await res.json();

  if (data.ok) {
    alert('Venta guardada ✔. ID: ' + data.sale_id);
    $('#productTable').DataTable().clear().draw();
  } else {
    alert('Error: ' + data.msg);
  }
}
