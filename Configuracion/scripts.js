document.addEventListener('DOMContentLoaded', function() {
    const configForm = document.getElementById('configForm');
    const btnGuardar = document.querySelector('.btn-guardar');
    const btnReset = document.querySelector('.btn-reset');

    // Manejar envío del formulario
    configForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Mostrar indicador de carga
        btnGuardar.disabled = true;
        btnGuardar.textContent = 'Guardando...';
        
        // Recopilar datos del formulario
        const formData = new FormData(configForm);
        
        // Enviar datos al servidor
        fetch('guardar_configuracion.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarMensaje('Configuración guardada exitosamente', 'success');
            } else {
                mostrarMensaje('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error de conexión. Intente nuevamente.', 'error');
        })
        .finally(() => {
            // Restaurar botón
            btnGuardar.disabled = false;
            btnGuardar.textContent = 'Guardar configuración';
        });
    });

    // Manejar botón de reset
    btnReset.addEventListener('click', function() {
        if (confirm('¿Está seguro de que desea restablecer todos los campos del formulario?')) {
            configForm.reset();
            mostrarMensaje('Formulario restablecido', 'info');
        }
    });

    // Función para mostrar mensajes
    function mostrarMensaje(mensaje, tipo) {
        // Crear elemento de mensaje
        const mensajeDiv = document.createElement('div');
        mensajeDiv.className = `mensaje mensaje-${tipo}`;
        mensajeDiv.textContent = mensaje;
        
        // Estilos del mensaje
        mensajeDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out;
        `;
        
        // Colores según tipo
        switch(tipo) {
            case 'success':
                mensajeDiv.style.backgroundColor = '#28a745';
                break;
            case 'error':
                mensajeDiv.style.backgroundColor = '#dc3545';
                break;
            case 'info':
                mensajeDiv.style.backgroundColor = '#17a2b8';
                break;
            default:
                mensajeDiv.style.backgroundColor = '#6c757d';
        }
        
        // Agregar al DOM
        document.body.appendChild(mensajeDiv);
        
        // Remover después de 5 segundos
        setTimeout(() => {
            mensajeDiv.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                if (mensajeDiv.parentNode) {
                    mensajeDiv.parentNode.removeChild(mensajeDiv);
                }
            }, 300);
        }, 5000);
    }

    // Agregar estilos CSS para animaciones
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Validación en tiempo real para campos requeridos
    const camposRequeridos = configForm.querySelectorAll('[required]');
    camposRequeridos.forEach(campo => {
        campo.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.style.borderColor = '#dc3545';
                this.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
            } else {
                this.style.borderColor = '#28a745';
                this.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
            }
        });
        
        campo.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = '#28a745';
                this.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
            } else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });
    });

    // Validación especial para email
    const campoEmail = document.getElementById('correo');
    if (campoEmail) {
        campoEmail.addEventListener('blur', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#dc3545';
                this.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
                mostrarMensaje('Por favor ingrese un email válido', 'error');
            }
        });
    }

    // Validación para NIT
    const campoNIT = document.getElementById('nit');
    if (campoNIT) {
        campoNIT.addEventListener('blur', function() {
            const nit = this.value.trim();
            if (nit && nit.length < 8) {
                this.style.borderColor = '#dc3545';
                this.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
                mostrarMensaje('El NIT debe tener al menos 8 caracteres', 'error');
            }
        });
    }

    // Validación para teléfono
    const campoTelefono = document.getElementById('telefono');
    if (campoTelefono) {
        campoTelefono.addEventListener('blur', function() {
            const telefono = this.value.trim();
            if (telefono && telefono.length < 7) {
                this.style.borderColor = '#dc3545';
                this.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
                mostrarMensaje('El teléfono debe tener al menos 7 dígitos', 'error');
            }
        });
    }
});
