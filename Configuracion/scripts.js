document.addEventListener('DOMContentLoaded', function() {
    const ivaInput = document.getElementById('iva');
    if (ivaInput) {
        ivaInput.addEventListener('input', function(e) {
            let val = ivaInput.value.replace(/[^0-9]/g, ''); 
            if (val.length > 3) val = val.slice(0, 3); 
            ivaInput.value = val ? val + '%' : '';
        });
        ivaInput.addEventListener('focus', function() {
            let val = ivaInput.value.replace(/[^0-9]/g, '');
            ivaInput.value = val;
        });
        ivaInput.addEventListener('blur', function() {
            let val = ivaInput.value.replace(/[^0-9]/g, '');
            if (val !== '') {
                ivaInput.value = val + '%';
            } else {
                ivaInput.value = '';
            }
        });
    }

    const form = document.getElementById('configForm');
    const storageKey = 'configFormData';
    let isDirty = false;

    if (form) {
        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });


        form.addEventListener('input', function() {
            isDirty = true;
        });
        form.addEventListener('change', function() {
            isDirty = true;
        });
    }

    // Advertencia al recargar si hay cambios sin guardar
    window.addEventListener('beforeunload', function(e) {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = 'Tienes cambios sin guardar. ¿Seguro que quieres salir?';
            return 'Tienes cambios sin guardar. ¿Seguro que quieres salir?';
        }
    });

    // Cargar datos guardados al iniciar
    if (form && localStorage.getItem(storageKey)) {
        const data = JSON.parse(localStorage.getItem(storageKey));
        for (const [key, value] of Object.entries(data)) {
            const field = form.elements[key];
            if (!field) continue;
            if (field.type === 'checkbox') {
                field.checked = value;
            } else if (field.type === 'radio') {
                if (field.value === value) field.checked = true;
            } else {
                field.value = value;
                if (key === 'iva' && !value.endsWith('%') && value !== '') field.value = value + '%';
            }
        }
        isDirty = false;
    }


    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // No recargar la página
            const data = {};
            for (const el of form.elements) {
                if (!el.name) continue;
                if (el.type === 'checkbox') {
                    data[el.name] = el.checked;
                } else if (el.type === 'radio') {
                    if (el.checked) data[el.name] = el.value;
                } else {
                    data[el.name] = el.value;
                }
            }
            localStorage.setItem(storageKey, JSON.stringify(data));
            isDirty = false;

            if (!document.getElementById('saveMsg')) {
                const msg = document.createElement('div');
                msg.id = 'saveMsg';
                msg.textContent = '¡Configuración guardada!';
                msg.style.position = 'fixed';
                msg.style.bottom = '32px';
                msg.style.right = '32px';
                msg.style.background = '#3498db';
                msg.style.color = '#fff';
                msg.style.padding = '12px 24px';
                msg.style.borderRadius = '8px';
                msg.style.boxShadow = '0 2px 8px rgba(52,152,219,0.15)';
                msg.style.zIndex = '9999';
                document.body.appendChild(msg);
                setTimeout(() => msg.remove(), 1800);
            }
        });


        form.addEventListener('reset', function(e) {
            const confirmed = confirm('¿Estás seguro de que deseas restablecer la configuración? Se perderán los cambios no guardados.');
            if (!confirmed) {
                e.preventDefault();
                return;
            }
            setTimeout(() => {
                localStorage.removeItem(storageKey);
                if (ivaInput) ivaInput.value = '19%';
                isDirty = false;
                // Mensaje de restablecido
                if (!document.getElementById('resetMsg')) {
                    const msg = document.createElement('div');
                    msg.id = 'resetMsg';
                    msg.textContent = '¡Configuración restablecida!';
                    msg.style.position = 'fixed';
                    msg.style.bottom = '32px';
                    msg.style.right = '32px';
                    msg.style.background = '#e67e22';
                    msg.style.color = '#fff';
                    msg.style.padding = '12px 24px';
                    msg.style.borderRadius = '8px';
                    msg.style.boxShadow = '0 2px 8px rgba(230,126,34,0.15)';
                    msg.style.zIndex = '9999';
                    document.body.appendChild(msg);
                    setTimeout(() => msg.remove(), 1800);
                }
            }, 0);
        });
    }
});
