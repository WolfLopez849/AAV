// JavaScript para la barra lateral y la generación de reportes
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM completamente cargado. main.js está ejecutándose.');

    // --- LÓGICA PARA LA BARRA LATERAL (TU CÓDIGO ORIGINAL) ---
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const appContainer = document.getElementById('appContainer');

    if (sidebarToggleBtn) {
        console.log('Botón de alternancia encontrado en main.js.');
        sidebarToggleBtn.addEventListener('click', function() {
            console.log('Botón de alternancia clicado en main.js.');
            appContainer.classList.toggle('collapsed');
        });
    } else {
        console.error('El botón con ID "sidebarToggleBtn" no fue encontrado en main.js.');
    }

    // --- NUEVA LÓGICA PARA GENERAR REPORTE ---
    const botonReporte = document.getElementById('btn-generar-reporte');

    // Se verifica que el botón de reporte exista
    if (botonReporte) {
        // Se añade el evento 'click' para el botón
        botonReporte.addEventListener('click', () => {
            
            // 1. Se muestra la ventana de confirmación
            const confirmacion = window.confirm("¿Estás seguro de que deseas cerrar la caja y generar el reporte final?");

            // 2. Si el usuario acepta, se procede a crear el PDF
            if (confirmacion) {
                console.log("Generando reporte PDF...");
                const elementoParaPdf = document.getElementById('reporte-para-pdf');

                const opcionesPdf = {
                    margin: 1,
                    filename: `ReporteDeCaja_${new Date().toISOString().slice(0, 10)}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                    
                };
                console.log("Reporte PDF generado.");
                // 3. Se usa la librería html2pdf para generar y guardar el archivo
                html2pdf().from(elementoParaPdf).set(opcionesPdf).save().then(() => {
                    window.location.href = "../abrir_caja_final/index.php";
                });
            } else {
                console.log("El usuario canceló la generación del reporte.");
            }
        });
    } else {
        console.error('El botón con ID "btn-generar-reporte" no fue encontrado. Revisa el ID en tu archivo HTML.');
    }
});