<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Menú - Configuración</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

    <div class="app-container" id="appContainer">
        <aside class="sidebar">
            <div class="logo">
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <span>POSNOVA</span>
            </div>
            <nav>
                <ul>
            <li onclick="location.href='../Menu/index.php'"><i class="fas fa-home"></i> <span>Menú principal</span></li>
          <li class="active" onclick="location.href='../inventario/inventario.html'"><i class="fas fa-boxes"></i> <span>Inventario</span></li>
          <li onclick="location.href='../ventas/index.php'"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></li>
          <li onclick="location.href='../clientes/clientes.php'"><i class="fas fa-users"></i> <span>Clientes</span></li>
          <li onclick="location.href='../proveedores/index.php'"><i class="fas fa-truck"></i> <span>Proveedores</span></li>
          <li onclick="location.href='../caja/index.html'"><i class="fas fa-cash-register"></i> <span>Caja</span></li>
          <li onclick="location.href='../reportes/index.php'"><i class="fas fa-chart-line"></i> <span>Reportes</span></li>
          <li onclick="location.href='../usuarios/Usuarios.php'"><i class="fas fa-user-cog"></i> <span>Usuarios</span></li>
          <li onclick="location.href='../configuracion/index.php'"><i class="fas fa-cog"></i> <span>Configuración</span></li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <header class="topbar">
                <div class="title">
                    <h2><i class="fas fa-cog"></i> Configuración</h2>
                </div>
                <div class="topbar-icons">
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-user-circle"></i>
                    <i class="fas fa-right-from-bracket logout"></i>
                </div>
            </header>
            <section>
                <div class="config-menu">
                    <form class="config-form" id="configForm" method="post" autocomplete="off">
                        <fieldset>
                            <legend>Datos del Comercio</legend>
                            <div class="form-group">
                                <label for="tipoPersona">Tipo de persona <span title="Selecciona si eres persona natural o jurídica">&#9432;</span></label>
                                <select id="tipoPersona" name="tipoPersona">
                                    <option value="natural">Persona Natural</option>
                                    <option value="juridica">Persona Jurídica</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nombreComercio">Nombre legal del comercio *</label>
                                <input type="text" id="nombreComercio" name="nombreComercio" placeholder="Ej: Mi Empresa S.A.S." required />
                            </div>
                            <div class="form-group">
                                <label for="nit">NIT / Número de identificación tributaria *</label>
                                <input type="text" id="nit" name="nit" placeholder="Ej: 900123456-7" required />
                                <span class="help-text" id="nitHelp"></span>
                            </div>
                            <div class="form-group">
                                <label for="ciiu">Actividad económica (CIIU) <span title="Código CIIU según DIAN">&#9432;</span></label>
                                <input type="text" id="ciiu" name="ciiu" placeholder="Ej: 4711" />
                            </div>
                            <div class="form-group">
                                <label for="regimen">Régimen tributario</label>
                                <select id="regimen" name="regimen">
                                    <option value="simple">Régimen Simple de Tributación (SIMPLE)</option>
                                    <option value="ordinario">Régimen Ordinario</option>
                                    <option value="especial">Régimen Especial</option>
                                    <option value="no_responsable_iva">No responsable de IVA</option>
                                    <option value="gran_contribuyente">Gran Contribuyente</option>
                                    <option value="entidad_no_contribuyente">Entidad No Contribuyente</option>
                                    <option value="entidad_exenta">Entidad Exenta</option>
                                </select>
                            </div>
                            <div class="form-group checkbox-group">
                                <label><input type="checkbox" name="responsableIVA" /> Responsable de IVA</label>
                                <label><input type="checkbox" name="responsableICA" /> Responsable de ICA</label>
                                <label><input type="checkbox" name="responsableRetefuente" /> Responsable de Retefuente</label>
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección principal *</label>
                                <input type="text" id="direccion" name="direccion" required />
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono de contacto *</label>
                                <input type="text" id="telefono" name="telefono" required />
                                <span class="help-text" id="telefonoHelp"></span>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo electrónico del comercio *</label>
                                <input type="email" id="correo" name="correo" required />
                                <span class="help-text" id="correoHelp"></span>
                            </div>
                            <div class="form-group">
                                <label for="ciudad">Ciudad *</label>
                                <input type="text" id="ciudad" name="ciudad" required placeholder="Ej: Bogotá" />
                            </div>
                            <div class="form-group">
                                <label for="departamento">Departamento *</label>
                                <input type="text" id="departamento" name="departamento" required placeholder="Ej: Cundinamarca" />
                            </div>
                            <div class="form-group">
                                <label for="web">Página web (opcional)</label>
                                <input type="url" id="web" name="web" />
                            </div>
                            <div class="form-group">
                                <label for="resolucion">Resolución de facturación DIAN *</label>
                                <input type="text" id="resolucion" name="resolucion" placeholder="Prefijo, número inicial y final, fecha de autorización" required />
                            </div>
                            <div class="form-group">
                                <label for="fechaInicio">Fecha de inicio de operaciones *</label>
                                <input type="date" id="fechaInicio" name="fechaInicio" required />
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Configuración de Facturación</legend>
                            <div class="form-group">
                                <label for="tipoFacturacion">Tipo de facturación</label>
                                <select id="tipoFacturacion" name="tipoFacturacion">
                                    <option>Electrónica</option>
                                    <option>Documento equivalente POS</option>
                                    <option>Ambos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="prefijoDIAN">Prefijo y rango autorizado por la DIAN *</label>
                                <input type="text" id="prefijoDIAN" name="prefijoDIAN" required />
                            </div>
                            <div class="form-group checkbox-group">
                                <label><input type="checkbox" name="consecutivoFacturas" /> Control de consecutivo automático para Facturas</label>
                                <label><input type="checkbox" name="consecutivoNotasCredito" /> Notas crédito</label>
                                <label><input type="checkbox" name="consecutivoNotasDebito" /> Notas débito</label>
                                <label><input type="checkbox" name="consecutivoDocumentosEquivalentes" /> Documentos equivalentes</label>
                            </div>
                            <div class="form-group checkbox-group">
                                <label><input type="checkbox" name="obligatorioNITCliente" /> NIT del cliente obligatorio para ventas superiores a $212.060</label>
                            </div>
                            <div class="form-group">
                                <label>Formato visual de factura/recibo:</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="formatoPersonalizable" /> Diseño personalizable</label>
                                    <label><input type="checkbox" name="formatoEncabezadoPie" /> Encabezado y pie de página personalizables</label>
                                    <label><input type="checkbox" name="formatoInfoAdicional" /> Inclusión de información adicional</label>
                                    <label><input type="checkbox" name="formatoQRDIAN" /> QR DIAN (si aplica)</label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Configuración de Impuestos</legend>
                            <div class="form-group">
                                <label for="iva">Porcentaje predeterminado de IVA</label>
                                <input type="text" id="iva" name="iva" class="percent-input" value="19%" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label>Aplicación del IVA:</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="aplicaIVAProducto" /> Por producto individual</label>
                                    <label><input type="checkbox" name="aplicaIVACategoria" /> Por categoría de productos</label>
                                    <label><input type="checkbox" name="aplicaIVAProveedor" /> Por proveedor</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Manejo de otros impuestos:</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="impuestoConsumo" /> Impuesto al consumo</label>
                                    <label><input type="checkbox" name="retefuente" /> Retefuente</label>
                                    <label><input type="checkbox" name="reteIVA" /> ReteIVA</label>
                                    <label><input type="checkbox" name="reteICA" /> ReteICA</label>
                                </div>
                            </div>
                            <div class="form-group checkbox-group">
                                <label><input type="checkbox" name="activarImpuestosLinea" /> Activar/desactivar impuestos por línea de producto</label>
                            </div>
                            <div class="form-group checkbox-group">
                                <label><input type="checkbox" name="soporteExentos" /> Soporte para productos exentos/excluidos</label>
                            </div>
                        </fieldset>
                        <!-- <fieldset>
                            <legend>Personalización y Apariencia</legend>
                            <div class="form-group">
                                <label for="logo">Logo del comercio</label>
                                <input type="file" id="logo" name="logo" accept="image/*" />
                                <img id="logoPreview" src="#" alt="Previsualización del logo" style="display:none;max-width:120px;margin-top:8px;" />
                            </div>
                            <div class="form-group">
                                <label for="moneda">Selección de moneda</label>
                                <select id="moneda" name="moneda">
                                    <option>COP</option>
                                    <option>USD</option>
                                    <option>EUR</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fechaHora">Formato de fecha y hora</label>
                                <input type="text" id="fechaHora" name="fechaHora" placeholder="Ej: DD/MM/YYYY HH:mm" />
                            </div>
                            <div class="form-group">
                                <label for="mensajeTicket">Mensaje predefinido para tickets/facturas</label>
                                <input type="text" id="mensajeTicket" name="mensajeTicket" placeholder="Gracias por su compra" />
                            </div>
                            <div class="form-group">
                                <label for="qrDIAN">QR de la DIAN (si aplica)</label>
                                <input type="file" id="qrDIAN" name="qrDIAN" accept="image/*" />
                            </div>
                        </fieldset> -->
                        <fieldset>
                            <legend>Control de Inventario</legend>
                            <div class="form-group">
                                <label for="unidadMedida">Unidad de medida</label>
                                <select id="unidadMedida" name="unidadMedida">
                                    <option>Unidad</option>
                                    <option>Kg</option>
                                    <option>Litro</option>
                                    <option>Paquete</option>
                                    <option>Metro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="stockMinimo">Monto mínimo de stock para alertas</label>
                                <input type="number" id="stockMinimo" name="stockMinimo" min="0" autocomplete="off" />
                            </div>
                            <div class="form-group checkbox-group">
                                <label><input type="checkbox" name="reposicionAuto" /> Políticas de reposición automática</label>
                                <label><input type="checkbox" name="controlLote" /> Activar/desactivar control de lote y vencimiento</label>
                                <label><input type="checkbox" name="controlSeriales" /> Aplicar control de seriales (opcional)</label>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Políticas Comerciales</legend>
                            <div class="form-group">
                                <label for="maxDescuento">Máximo descuento permitido por usuario (%)</label>
                                <input type="number" id="maxDescuento" name="maxDescuento" min="0" max="100" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label>Tipos de descuento:</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="tipoDescuentoProducto" /> Por producto</label>
                                    <label><input type="checkbox" name="tipoDescuentoCategoria" /> Por categoría</label>
                                    <label><input type="checkbox" name="tipoDescuentoVIP" /> Cliente VIP</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="politicaDevoluciones">Política de devoluciones y cambios</label>
                                <input type="text" id="politicaDevoluciones" name="politicaDevoluciones" />
                            </div>
                            <div class="form-group">
                                <label>Cálculo de precios:</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="precioBase" /> Precio base + IVA</label>
                                    <label><input type="checkbox" name="precioFinal" /> Precio final ya con IVA incluido</label>
                                    <label><input type="checkbox" name="redondeo" /> Redondeo de montos (opcional)</label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Configuración de Métodos de Pago</legend>
                            <div class="form-group">
                                <label>Métodos aceptados:</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="metodoPagoEfectivo" /> Efectivo</label>
                                    <label><input type="checkbox" name="metodoPagoTarjeta" /> Tarjeta débito/crédito</label>
                                    <label><input type="checkbox" name="metodoPagoTransferencia" /> Transferencia</label>
                                    <label><input type="checkbox" name="metodoPagoQR" /> Código QR / Nequi / Daviplata</label>
                                    <label><input type="checkbox" name="metodoPagoPSE" /> PSE</label>
                                    <label><input type="checkbox" name="metodoPagoPropina" /> Propina voluntaria</label>
                                </div>
                            </div>
                            <div class="form-group checkbox-group">
                                <label><input type="checkbox" name="pagoMixto" /> Activar pago mixto (combinar diferentes métodos)</label>
                            </div>
                            <div class="form-group">
                                <label for="recargoMetodo">Establecer recargo por método (%)</label>
                                <input type="number" id="recargoMetodo" name="recargoMetodo" min="0" max="100" autocomplete="off" />
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Consentimiento y Legal</legend>
                            <div class="form-group full-width" style="margin-top: 18px;">
                                <label class="only-label" style="display: flex; align-items: center; gap: 10px;">
                                    <input type="checkbox" name="consentimientoDatos" id="consentimientoDatos" required checked />
                                    Acepto la <a href="#" target="_blank">política de tratamiento de datos personales</a> y la <a href="#" target="_blank">política de privacidad</a> *
                                </label>
                            </div>
                        </fieldset>
                        <div class="form-actions">
                            <button type="submit" class="btn-guardar">Guardar configuración</button>
                            <button type="reset" class="btn-reset">Restablecer</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script src="../Menu/scripts.js"></script>
    <script src="scripts.js"></script>
</body>
</html>
