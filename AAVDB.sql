-- ESTE ARCHIVO ES PARA IMPORTAL AL PHPMYADMIN NO EJECUTAR.
-- ESPERO QUE LEAN Y NO ME PREGUNTEN COMO HACERLO, YA QUE EL QUE ME PREGUNTE LE VOY PARTIENDO EL PALO DE LA ESCOBA.
-- ADVERTIDOS QUEDAN;
DROP DATABASE IF EXISTS AAVDB;
CREATE DATABASE IF NOT EXISTS AAVDB;
USE AAVDB;

CREATE TABLE IF NOT EXISTS `caja` (
  `item_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_client` VARCHAR(255) DEFAULT NULL,
  `document` VARCHAR(255) DEFAULT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `price_unit` DECIMAL(12,2) NOT NULL,
  `iva_percent` DECIMAL(5,2) NOT NULL,
  `qty` int UNSIGNED NOT NULL,
  `total_line` DECIMAL(14,2) NOT NULL,
  `code` VARCHAR(40) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `category` VARCHAR(60) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `tipo_doc` varchar(50) NOT NULL,
  `numero_doc` int NOT NULL,
  `telefono` int NOT NULL,
  `correo` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `productos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `codigo` VARCHAR(50) UNIQUE NOT NULL,
  `precioCompra` DECIMAL(10,2) NOT NULL,
  `precioVenta` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL,
  `categoria` VARCHAR(100),
  `iva` INT NOT NULL DEFAULT 19,
  `proveedor` VARCHAR(100),
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `usuario` varchar(80) NOT NULL,
  `contrasena` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rol` enum('Administrador','Cajero','Supervisor') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `estado` enum('activo','inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `bitacora` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `accion` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `accesos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `configuracion_sistema` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo_persona` varchar(20) NOT NULL,
  `nombre_comercio` varchar(200) NOT NULL,
  `nit` varchar(50) NOT NULL,
  `ciiu` varchar(20),
  `regimen` varchar(50),
  `responsable_iva` tinyint(1) DEFAULT 0,
  `responsable_ica` tinyint(1) DEFAULT 0,
  `responsable_retefuente` tinyint(1) DEFAULT 0,
  `direccion` text NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `departamento` varchar(100) NOT NULL,
  `web` varchar(200),
  `resolucion` varchar(200) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `tipo_facturacion` varchar(50),
  `prefijo_dian` varchar(50),
  `consecutivo_facturas` tinyint(1) DEFAULT 0,
  `consecutivo_notas_credito` tinyint(1) DEFAULT 0,
  `consecutivo_notas_debito` tinyint(1) DEFAULT 0,
  `consecutivo_documentos_equivalentes` tinyint(1) DEFAULT 0,
  `obligatorio_nit_cliente` tinyint(1) DEFAULT 0,
  `formato_personalizable` tinyint(1) DEFAULT 0,
  `formato_encabezado_pie` tinyint(1) DEFAULT 0,
  `formato_info_adicional` tinyint(1) DEFAULT 0,
  `formato_qr_dian` tinyint(1) DEFAULT 0,
  `iva_porcentaje` decimal(5,2) DEFAULT 19.00,
  `aplica_iva_producto` tinyint(1) DEFAULT 0,
  `aplica_iva_categoria` tinyint(1) DEFAULT 0,
  `aplica_iva_proveedor` tinyint(1) DEFAULT 0,
  `impuesto_consumo` tinyint(1) DEFAULT 0,
  `retefuente` tinyint(1) DEFAULT 0,
  `rete_iva` tinyint(1) DEFAULT 0,
  `rete_ica` tinyint(1) DEFAULT 0,
  `activar_impuestos_linea` tinyint(1) DEFAULT 0,
  `soporte_exentos` tinyint(1) DEFAULT 0,
  `unidad_medida` varchar(20),
  `stock_minimo` int DEFAULT 0,
  `reposicion_auto` tinyint(1) DEFAULT 0,
  `control_lote` tinyint(1) DEFAULT 0,
  `control_seriales` tinyint(1) DEFAULT 0,
  `max_descuento` decimal(5,2) DEFAULT 0.00,
  `tipo_descuento_producto` tinyint(1) DEFAULT 0,
  `tipo_descuento_categoria` tinyint(1) DEFAULT 0,
  `tipo_descuento_vip` tinyint(1) DEFAULT 0,
  `politica_devoluciones` text,
  `precio_base` tinyint(1) DEFAULT 0,
  `precio_final` tinyint(1) DEFAULT 0,
  `redondeo` tinyint(1) DEFAULT 0,
  `metodo_pago_efectivo` tinyint(1) DEFAULT 0,
  `metodo_pago_tarjeta` tinyint(1) DEFAULT 0,
  `metodo_pago_transferencia` tinyint(1) DEFAULT 0,
  `metodo_pago_qr` tinyint(1) DEFAULT 0,
  `metodo_pago_pse` tinyint(1) DEFAULT 0,
  `metodo_pago_propina` tinyint(1) DEFAULT 0,
  `pago_mixto` tinyint(1) DEFAULT 0,
  `recargo_metodo` decimal(5,2) DEFAULT 0.00,
  `consentimiento_datos` tinyint(1) DEFAULT 0,
  `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS caja_estado (
  id INT PRIMARY KEY DEFAULT 1,
  estado ENUM('abierta','cerrada') NOT NULL DEFAULT 'cerrada',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO caja_estado (id, estado) VALUES (1, 'cerrada');