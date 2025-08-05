// =========================
// Mostrar campo correcto según tipo de filtro
// =========================
document.getElementById("tipo-filtro").addEventListener("change", function () {
    // Ocultar todos
    document.getElementById("filtro-dia").style.display = "none";
    document.getElementById("filtro-mes").style.display = "none";
    document.getElementById("filtro-anio").style.display = "none";

    // Mostrar el que corresponda
    if (this.value === "dia") {
        document.getElementById("filtro-dia").style.display = "block";
    } else if (this.value === "mes") {
        document.getElementById("filtro-mes").style.display = "block";
    } else if (this.value === "anio") {
        document.getElementById("filtro-anio").style.display = "block";
    }
});

// =========================
// Aplicar filtro
// =========================
document.getElementById("btn-filtrar").addEventListener("click", function () {
    const tipo = document.getElementById("tipo-filtro").value;
    let valor = "";

    if (tipo === "dia") {
        valor = document.getElementById("filtro-dia").value;
    } else if (tipo === "mes") {
        valor = document.getElementById("filtro-mes").value;
    } else if (tipo === "anio") {
        valor = document.getElementById("filtro-anio").value;
    }

    if (valor) {
        cargarReportes(tipo, valor);
    } else {
        alert("Selecciona un valor para el filtro");
    }
});

// =========================
// Cargar reportes desde PHP/MySQL
// =========================
async function cargarReportes(filtro = "", valor = "") {
    try {
        // Llamar al archivo correcto que sí se conecta a MySQL
        const url = filtro 
            ? `datareportes.php?filtro=${filtro}&valor=${encodeURIComponent(valor)}` 
            : "datareportes.php";

        const res = await fetch(url);
        const data = await res.json();

        if (data.error) {
            console.error("Error desde PHP:", data.error);
            return;
        }

        // === Tarjetas de ventas ===
        document.getElementById("total-hoy").textContent = "$" + formatearNumero(data.ventas.hoy);
        document.getElementById("total-semana").textContent = "$" + formatearNumero(data.ventas.semana);
        document.getElementById("total-mes").textContent = "$" + formatearNumero(data.ventas.mes);
        document.getElementById("crecimiento").textContent = data.ventas.crecimiento + "%";

        // === Tarjetas de ingresos y ganancias/pérdidas ===
        document.getElementById("ingresos-totales").textContent = "$" + formatearNumero(data.ingresos.totales);
        document.getElementById("ganancias").textContent = "$" + formatearNumero(data.ingresos.ganancias);
        document.getElementById("perdidas").textContent = "$" + formatearNumero(data.ingresos.perdidas);

        // === Llenar tabla de detalle ===
        llenarTabla(data.detalle);

        // === Gráfico circular ===
        generarGraficoCircular(data.graficoCircular.labels, data.graficoCircular.data);

        // === Gráfico de columnas ===
        generarGraficoColumnas(data.graficoColumnas.labels, data.graficoColumnas.data);

    } catch (error) {
        console.error("Error cargando reportes:", error);
    }
}

// =========================
// Formatear números como moneda
// =========================
function formatearNumero(num) {
    return Number(num).toLocaleString("es-CO", { minimumFractionDigits: 0 });
}

// =========================
// Llenar tabla
// =========================
function llenarTabla(detalle) {
    const tbody = document.getElementById("tabla-body");
    tbody.innerHTML = "";

    if (!detalle || detalle.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">No hay datos para este filtro</td></tr>`;
        return;
    }

    detalle.forEach(item => {
        const fila = `
            <tr>
                <td>${item.fecha}</td>
                <td>${item.productos}</td>
                <td>$${formatearNumero(item.total)}</td>
                <td>$${formatearNumero(item.costo)}</td>
                <td>$${formatearNumero(item.ganancia)}</td>
            </tr>
        `;
        tbody.innerHTML += fila;
    });
}

// =========================
// Gráfico circular - Distribución de ventas
// =========================
let chartCircular;
function generarGraficoCircular(labels, data) {
    const ctx = document.getElementById("graficoCircular").getContext("2d");

    if (chartCircular) {
        chartCircular.destroy();
    }

    chartCircular = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', 
                    '#66BB6A', '#BA68C8', '#FFA726'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
}

// =========================
// Gráfico de columnas - Ventas por día
// =========================
let chartColumnas;
function generarGraficoColumnas(labels, data) {
    const ctx = document.getElementById("graficoColumnas").getContext("2d");

    if (chartColumnas) {
        chartColumnas.destroy();
    }

    chartColumnas = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ventas por día',
                data: data,
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// =========================
// Cargar datos iniciales
// =========================
cargarReportes();
