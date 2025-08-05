let seleccionado = null;

// Abrir modal para agregar
document.getElementById("btnAgregarUsuario").addEventListener("click", () => {
  mostrarModal("nuevo");
});

// Botón cerrar modal
document.getElementById("btnCerrarModal").addEventListener("click", cerrarModal);

// Función mostrar modal
function mostrarModal(tipo) {
  const modal = document.getElementById("modalUsuario");
  modal.style.display = "flex";

  if (tipo === "nuevo") {
    document.getElementById("modalTitulo").textContent = "Nuevo Usuario";
    document.getElementById("formUsuario").reset();
    document.getElementById("btnSubmit").name = "guardar";
    document.getElementById("btnSubmit").innerHTML = '<i class="fas fa-save"></i> Guardar';
  } else if (tipo === "editar" && seleccionado) {
    const id = seleccionado.getAttribute("data-id");
    const nombre = seleccionado.getAttribute("data-nombre");
    const usuario = seleccionado.getAttribute("data-usuario");
    const rol = seleccionado.getAttribute("data-rol");
    const estado = seleccionado.getAttribute("data-estado");

    document.getElementById("idUsuario").value = id;
    document.getElementById("nombre").value = nombre;
    document.getElementById("usuario").value = usuario;
    document.getElementById("rol").value = rol;
    document.getElementById("estado").value = estado;

    document.getElementById("contrasena").value = "";
    document.getElementById("btnSubmit").name = "actualizar";
    document.getElementById("btnSubmit").innerHTML = '<i class="fas fa-save"></i> Actualizar';
    document.getElementById("modalTitulo").textContent = "Editar Usuario";
  }
}

// Función cerrar modal
function cerrarModal() {
  document.getElementById("modalUsuario").style.display = "none";
}

// Selección de filas
document.querySelectorAll(".tabla-usuarios tbody tr").forEach(row => {
  row.addEventListener("click", () => {
    if (seleccionado === row) {
      row.classList.remove("seleccionado");
      document.getElementById("btnEditar").style.display = "none";
      document.getElementById("btnEliminar").style.display = "none";
      seleccionado = null;
      return;
    }

    document.querySelectorAll(".tabla-usuarios tbody tr").forEach(r => r.classList.remove("seleccionado"));
    row.classList.add("seleccionado");
    seleccionado = row;

    const id = row.getAttribute("data-id");
    document.getElementById("eliminarId").value = id;
    document.getElementById("btnEditar").style.display = "inline-block";
    document.getElementById("btnEliminar").style.display = "inline-block";
  });
});

// Deseleccionar al hacer clic fuera de la tabla o modal
document.addEventListener("click", (e) => {
  const table = document.querySelector(".tabla-usuarios");
  const modal = document.getElementById("modalUsuario");

  if (!table.contains(e.target) && !modal.contains(e.target) && seleccionado) {
    seleccionado.classList.remove("seleccionado");
    seleccionado = null;
    document.getElementById("btnEditar").style.display = "none";
    document.getElementById("btnEliminar").style.display = "none";
  }
});

// Botón editar
document.getElementById("btnEditar").addEventListener("click", () => {
  if (seleccionado) {
    mostrarModal("editar");
  }
});

// Confirmación antes de eliminar
document.getElementById("formEliminar").addEventListener("submit", function(e) {
  if (!confirm("¿Estás seguro de eliminar este usuario?")) {
    e.preventDefault();
  }
});

// Mostrar/ocultar contraseña en modal
const togglePassword = document.getElementById("togglePassword");
const inputPassword = document.getElementById("contrasena");

togglePassword.addEventListener("click", () => {
  if (inputPassword.type === "password") {
    inputPassword.type = "text";
    togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
  } else {
    inputPassword.type = "password";
    togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
  }
});

// Mostrar ícono de ojo solo si hay texto
inputPassword.addEventListener("input", () => {
  togglePassword.style.display = inputPassword.value ? "block" : "none";
});

// Sidebar toggle
document.getElementById("sidebarToggleBtn").addEventListener("click", () => {
  document.getElementById("appContainer").classList.toggle("collapsed");
});

// ===================== TOAST SYSTEM ===================== //
function showToast(message, type = "info") {
  const toast = document.createElement("div");
  toast.classList.add("toast", type);
  toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i> ${message}`;

  document.body.appendChild(toast);

  // Mostrar toast
  setTimeout(() => toast.classList.add("show"), 50);

  // Ocultar después de 3 segundos
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 400);
  }, 3000);
}

// Mostrar toast según status en URL
const params = new URLSearchParams(window.location.search);
const status = params.get('status');

if (status) {
  let mensaje = '';
  let tipo = '';

  if (status === 'creado') {
    mensaje = 'Usuario creado con éxito';
    tipo = 'success';
  } else if (status === 'actualizado') {
    mensaje = 'Usuario actualizado correctamente';
    tipo = 'info';
  } else if (status === 'eliminado') {
    mensaje = 'Usuario eliminado';
    tipo = 'error';
  }

  if (mensaje) {
    showToast(mensaje, tipo);
  }

  // Limpiar parámetros de la URL
  window.history.replaceState({}, document.title, window.location.pathname);
}

// ===================== MOSTRAR CONTRASEÑA EN TABLA ===================== //
document.querySelectorAll(".btn-ver").forEach((btn) => {
  btn.addEventListener("click", () => {
    const input = btn.previousElementSibling;

    // Cambiar a tipo texto por 3 segundos
    input.type = "text";
    setTimeout(() => {
      input.type = "password";
    }, 3000);

    // Mostrar toast informativo
    showToast("La contraseña se muestra encriptada por motivos de seguridad. Para cambiarla, use 'Editar'.", "info");
  });
});

// ===================== BUSCADOR EN TABLA ===================== //
const buscarInput = document.getElementById("buscarInput");
const filasUsuarios = document.querySelectorAll(".tabla-usuarios tbody tr");

if (buscarInput) {
  buscarInput.addEventListener("input", () => {
    const filtro = buscarInput.value.toLowerCase();

    filasUsuarios.forEach(fila => {
      const nombre = fila.querySelector("td:nth-child(1)").textContent.toLowerCase();
      const usuario = fila.querySelector("td:nth-child(2)").textContent.toLowerCase();
      const rol = fila.querySelector("td:nth-child(3)").textContent.toLowerCase();
      const estado = fila.querySelector("td:nth-child(4)").textContent.toLowerCase();
      const creadoPor = fila.querySelector("td:nth-child(7)").textContent.toLowerCase();

      if (
        nombre.includes(filtro) ||
        usuario.includes(filtro) ||
        rol.includes(filtro) ||
        estado.includes(filtro) ||
        creadoPor.includes(filtro)
      ) {
        fila.style.display = "";
      } else {
        fila.style.display = "none";
      }
    });
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