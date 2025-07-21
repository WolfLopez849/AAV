-- ESTE ARCHIVO ES PARA IMPORTAL AL PHPMYADMIN NO EJECUTAR.
-- ESPERO QUE LEAN Y NO ME PREGUNTEN COMO HACERLO, YA QUE EL QUE ME PREGUNTE LE VOY PARTIENDO EL PALO DE LA ESCOBA.
-- ADVERTIDOS QUEDAN;
DROP DATABASE AAV;
CREATE DATABASE AAV;
USE AAV;

-- Estructura de tabla para la tabla `productos`
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `categoria` varchar(80) NOT NULL,
  `stock` int NOT NULL,
  `entradas` int NOT NULL,
  `salidas` int NOT NULL,
  `vendidos` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estructura de tabla para la tabla `proveedores`
DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `contacto` varchar(80) NOT NULL,
  `productos` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estructura de tabla para la tabla `usuarios`

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `documento` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(80) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `password` varchar(100) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `rol` varchar(80) NOT NULL,
  PRIMARY KEY (`documento`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estructura de tabla para la tabla `ventas`

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` varchar(80) NOT NULL,
  `hora` varchar(80) NOT NULL,
  `productos` varchar(225) NOT NULL,
  `total` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

