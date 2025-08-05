<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener datos del formulario
        $tipoPersona = $_POST['tipoPersona'] ?? '';
        $nombreComercio = $_POST['nombreComercio'] ?? '';
        $nit = $_POST['nit'] ?? '';
        $ciiu = $_POST['ciiu'] ?? '';
        $regimen = $_POST['regimen'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $departamento = $_POST['departamento'] ?? '';
        $web = $_POST['web'] ?? '';
        $resolucion = $_POST['resolucion'] ?? '';
        $fechaInicio = $_POST['fechaInicio'] ?? '';
        $tipoFacturacion = $_POST['tipoFacturacion'] ?? '';
        $prefijoDIAN = $_POST['prefijoDIAN'] ?? '';
        $iva = $_POST['iva'] ?? '19%';
        $unidadMedida = $_POST['unidadMedida'] ?? '';
        $stockMinimo = $_POST['stockMinimo'] ?? 0;
        $maxDescuento = $_POST['maxDescuento'] ?? 0;
        $politicaDevoluciones = $_POST['politicaDevoluciones'] ?? '';
        $recargoMetodo = $_POST['recargoMetodo'] ?? 0;

        // Procesar checkboxes (convertir a 0 o 1)
        $responsableIVA = isset($_POST['responsableIVA']) ? 1 : 0;
        $responsableICA = isset($_POST['responsableICA']) ? 1 : 0;
        $responsableRetefuente = isset($_POST['responsableRetefuente']) ? 1 : 0;
        $consecutivoFacturas = isset($_POST['consecutivoFacturas']) ? 1 : 0;
        $consecutivoNotasCredito = isset($_POST['consecutivoNotasCredito']) ? 1 : 0;
        $consecutivoNotasDebito = isset($_POST['consecutivoNotasDebito']) ? 1 : 0;
        $consecutivoDocumentosEquivalentes = isset($_POST['consecutivoDocumentosEquivalentes']) ? 1 : 0;
        $obligatorioNITCliente = isset($_POST['obligatorioNITCliente']) ? 1 : 0;
        $formatoPersonalizable = isset($_POST['formatoPersonalizable']) ? 1 : 0;
        $formatoEncabezadoPie = isset($_POST['formatoEncabezadoPie']) ? 1 : 0;
        $formatoInfoAdicional = isset($_POST['formatoInfoAdicional']) ? 1 : 0;
        $formatoQRDIAN = isset($_POST['formatoQRDIAN']) ? 1 : 0;
        $aplicaIVAProducto = isset($_POST['aplicaIVAProducto']) ? 1 : 0;
        $aplicaIVACategoria = isset($_POST['aplicaIVACategoria']) ? 1 : 0;
        $aplicaIVAProveedor = isset($_POST['aplicaIVAProveedor']) ? 1 : 0;
        $impuestoConsumo = isset($_POST['impuestoConsumo']) ? 1 : 0;
        $retefuente = isset($_POST['retefuente']) ? 1 : 0;
        $reteIVA = isset($_POST['reteIVA']) ? 1 : 0;
        $reteICA = isset($_POST['reteICA']) ? 1 : 0;
        $activarImpuestosLinea = isset($_POST['activarImpuestosLinea']) ? 1 : 0;
        $soporteExentos = isset($_POST['soporteExentos']) ? 1 : 0;
        $reposicionAuto = isset($_POST['reposicionAuto']) ? 1 : 0;
        $controlLote = isset($_POST['controlLote']) ? 1 : 0;
        $controlSeriales = isset($_POST['controlSeriales']) ? 1 : 0;
        $tipoDescuentoProducto = isset($_POST['tipoDescuentoProducto']) ? 1 : 0;
        $tipoDescuentoCategoria = isset($_POST['tipoDescuentoCategoria']) ? 1 : 0;
        $tipoDescuentoVIP = isset($_POST['tipoDescuentoVIP']) ? 1 : 0;
        $precioBase = isset($_POST['precioBase']) ? 1 : 0;
        $precioFinal = isset($_POST['precioFinal']) ? 1 : 0;
        $redondeo = isset($_POST['redondeo']) ? 1 : 0;
        $metodoPagoEfectivo = isset($_POST['metodoPagoEfectivo']) ? 1 : 0;
        $metodoPagoTarjeta = isset($_POST['metodoPagoTarjeta']) ? 1 : 0;
        $metodoPagoTransferencia = isset($_POST['metodoPagoTransferencia']) ? 1 : 0;
        $metodoPagoQR = isset($_POST['metodoPagoQR']) ? 1 : 0;
        $metodoPagoPSE = isset($_POST['metodoPagoPSE']) ? 1 : 0;
        $metodoPagoPropina = isset($_POST['metodoPagoPropina']) ? 1 : 0;
        $pagoMixto = isset($_POST['pagoMixto']) ? 1 : 0;
        $consentimientoDatos = isset($_POST['consentimientoDatos']) ? 1 : 0;

        // Limpiar el porcentaje de IVA
        $ivaPorcentaje = str_replace('%', '', $iva);

        // Verificar si ya existe configuración
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM configuracion_sistema");
        $existe = $stmt->fetch()['count'] > 0;

        if ($existe) {
            // Actualizar configuración existente
            $sql = "UPDATE configuracion_sistema SET 
                tipo_persona = ?, nombre_comercio = ?, nit = ?, ciiu = ?, regimen = ?,
                responsable_iva = ?, responsable_ica = ?, responsable_retefuente = ?,
                direccion = ?, telefono = ?, correo = ?, ciudad = ?, departamento = ?,
                web = ?, resolucion = ?, fecha_inicio = ?, tipo_facturacion = ?,
                prefijo_dian = ?, consecutivo_facturas = ?, consecutivo_notas_credito = ?,
                consecutivo_notas_debito = ?, consecutivo_documentos_equivalentes = ?,
                obligatorio_nit_cliente = ?, formato_personalizable = ?, formato_encabezado_pie = ?,
                formato_info_adicional = ?, formato_qr_dian = ?, iva_porcentaje = ?,
                aplica_iva_producto = ?, aplica_iva_categoria = ?, aplica_iva_proveedor = ?,
                impuesto_consumo = ?, retefuente = ?, rete_iva = ?, rete_ica = ?,
                activar_impuestos_linea = ?, soporte_exentos = ?, unidad_medida = ?,
                stock_minimo = ?, reposicion_auto = ?, control_lote = ?, control_seriales = ?,
                max_descuento = ?, tipo_descuento_producto = ?, tipo_descuento_categoria = ?,
                tipo_descuento_vip = ?, politica_devoluciones = ?, precio_base = ?,
                precio_final = ?, redondeo = ?, metodo_pago_efectivo = ?, metodo_pago_tarjeta = ?,
                metodo_pago_transferencia = ?, metodo_pago_qr = ?, metodo_pago_pse = ?,
                metodo_pago_propina = ?, pago_mixto = ?, recargo_metodo = ?, consentimiento_datos = ?
                WHERE id = 1";
        } else {
            // Insertar nueva configuración
            $sql = "INSERT INTO configuracion_sistema (
                tipo_persona, nombre_comercio, nit, ciiu, regimen, responsable_iva, 
                responsable_ica, responsable_retefuente, direccion, telefono, correo, 
                ciudad, departamento, web, resolucion, fecha_inicio, tipo_facturacion,
                prefijo_dian, consecutivo_facturas, consecutivo_notas_credito,
                consecutivo_notas_debito, consecutivo_documentos_equivalentes,
                obligatorio_nit_cliente, formato_personalizable, formato_encabezado_pie,
                formato_info_adicional, formato_qr_dian, iva_porcentaje,
                aplica_iva_producto, aplica_iva_categoria, aplica_iva_proveedor,
                impuesto_consumo, retefuente, rete_iva, rete_ica, activar_impuestos_linea,
                soporte_exentos, unidad_medida, stock_minimo, reposicion_auto,
                control_lote, control_seriales, max_descuento, tipo_descuento_producto,
                tipo_descuento_categoria, tipo_descuento_vip, politica_devoluciones,
                precio_base, precio_final, redondeo, metodo_pago_efectivo,
                metodo_pago_tarjeta, metodo_pago_transferencia, metodo_pago_qr,
                metodo_pago_pse, metodo_pago_propina, pago_mixto, recargo_metodo,
                consentimiento_datos
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

        $stmt = $pdo->prepare($sql);
        $params = [
            $tipoPersona, $nombreComercio, $nit, $ciiu, $regimen, $responsableIVA,
            $responsableICA, $responsableRetefuente, $direccion, $telefono, $correo,
            $ciudad, $departamento, $web, $resolucion, $fechaInicio, $tipoFacturacion,
            $prefijoDIAN, $consecutivoFacturas, $consecutivoNotasCredito,
            $consecutivoNotasDebito, $consecutivoDocumentosEquivalentes,
            $obligatorioNITCliente, $formatoPersonalizable, $formatoEncabezadoPie,
            $formatoInfoAdicional, $formatoQRDIAN, $ivaPorcentaje,
            $aplicaIVAProducto, $aplicaIVACategoria, $aplicaIVAProveedor,
            $impuestoConsumo, $retefuente, $reteIVA, $reteICA, $activarImpuestosLinea,
            $soporteExentos, $unidadMedida, $stockMinimo, $reposicionAuto,
            $controlLote, $controlSeriales, $maxDescuento, $tipoDescuentoProducto,
            $tipoDescuentoCategoria, $tipoDescuentoVIP, $politicaDevoluciones,
            $precioBase, $precioFinal, $redondeo, $metodoPagoEfectivo,
            $metodoPagoTarjeta, $metodoPagoTransferencia, $metodoPagoQR,
            $metodoPagoPSE, $metodoPagoPropina, $pagoMixto, $recargoMetodo,
            $consentimientoDatos
        ];

        $stmt->execute($params);

        echo json_encode([
            'success' => true,
            'message' => 'Configuración guardada exitosamente'
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar la configuración: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
?> 