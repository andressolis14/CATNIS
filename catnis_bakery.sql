-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-04-2026 a las 08:33:28
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `catnis_bakery`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abonos`
--

CREATE TABLE `abonos` (
  `id` int(11) NOT NULL,
  `deuda_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','transferencia','otros') DEFAULT 'efectivo',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `nota` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `abonos`
--

INSERT INTO `abonos` (`id`, `deuda_id`, `monto`, `metodo_pago`, `fecha`, `nota`) VALUES
(1, 1, 12.00, 'efectivo', '2026-04-03 22:16:55', ''),
(2, 1, 0.50, 'efectivo', '2026-04-06 12:53:03', ''),
(3, 2, 2.00, 'efectivo', '2026-04-06 15:32:47', ''),
(4, 2, 4.00, 'efectivo', '2026-04-06 22:16:05', ''),
(5, 3, 10.00, 'efectivo', '2026-04-09 13:14:24', ''),
(6, 3, 5.00, 'transferencia', '2026-04-09 13:14:56', 'termino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `nombre_mascota` varchar(100) DEFAULT NULL,
  `cumpleanos_mascota` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `telefono`, `correo`, `direccion`, `nombre_mascota`, `cumpleanos_mascota`, `activo`, `created_at`) VALUES
(1, 'Cliente General', '', '', NULL, NULL, NULL, 0, '2026-03-30 21:03:25'),
(2, 'María García', '555-1234', 'maria@email.com', NULL, NULL, NULL, 1, '2026-03-30 21:03:25'),
(3, 'Carlos López', '3041317929', 'carlos@email.com', '', 'scoot', '2026-04-09', 1, '2026-03-30 21:03:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_gastos`
--

CREATE TABLE `detalle_gastos` (
  `id` int(11) NOT NULL,
  `gasto_id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `monto` decimal(10,2) NOT NULL,
  `item_maestro_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_gastos`
--

INSERT INTO `detalle_gastos` (`id`, `gasto_id`, `descripcion`, `cantidad`, `monto`, `item_maestro_id`) VALUES
(11, 6, 'harina', 1, 2000.00, 1),
(12, 6, 'HARINA', 1, 50000.00, 1),
(13, 6, 'harina', 1, 4000.00, 1),
(14, 6, 'POLLO', 1, 8000.00, 2),
(15, 7, 'arroz', 1, 1000.00, 3),
(16, 8, 'harina', 1, 2000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(5, 5, 4, 5, 2.50, 12.50),
(6, 6, 4, 5, 2.50, 12.50),
(7, 7, 2, 4, 1.50, 6.00),
(8, 8, 5, 4, 4.00, 16.00),
(9, 9, 1, 1, 1.00, 1.00),
(10, 10, 3, 1, 15.00, 15.00),
(11, 11, 3, 9, 15.00, 135.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deudas`
--

CREATE TABLE `deudas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `abonado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `saldo` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','parcial','pagada') DEFAULT 'pendiente',
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `deudas`
--

INSERT INTO `deudas` (`id`, `venta_id`, `cliente_id`, `total`, `abonado`, `saldo`, `estado`, `fecha`) VALUES
(1, 5, 3, 12.50, 12.50, 0.00, 'pagada', '2026-04-03'),
(2, 7, 2, 6.00, 6.00, 0.00, 'pagada', '2026-04-06'),
(3, 10, 3, 15.00, 15.00, 0.00, 'pagada', '2026-04-09'),
(4, 11, 2, 135.00, 0.00, 135.00, 'pendiente', '2026-04-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `numero_factura` varchar(50) DEFAULT NULL,
  `descripcion_extra` varchar(255) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `categoria` enum('servicios','compras','transporte','nomina','alquiler','prestamos','activos','otros') DEFAULT 'otros',
  `metodo_pago` enum('efectivo','transferencia','otros') NOT NULL DEFAULT 'efectivo',
  `fecha` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id`, `usuario_id`, `numero_factura`, `descripcion_extra`, `monto`, `categoria`, `fecha`, `descripcion`, `created_at`) VALUES
(6, 1, '', NULL, 64000.00, 'otros', '2026-04-09', '', '2026-04-09 15:12:43'),
(7, 1, '', NULL, 1000.00, 'otros', '2026-04-09', '', '2026-04-09 22:31:18'),
(8, 1, '', NULL, 2000.00, 'otros', '2026-04-09', '', '2026-04-09 22:37:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items_maestro_gastos`
--

CREATE TABLE `items_maestro_gastos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `unidad_medida` varchar(50) NOT NULL DEFAULT 'unid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `items_maestro_gastos`
--

INSERT INTO `items_maestro_gastos` (`id`, `codigo`, `nombre`, `created_at`, `fecha_creacion`) VALUES
(1, 'ITEM-90111', 'harina', '2026-04-09 15:12:43', '2026-04-09 15:18:01'),
(2, 'ITEM-055E8', 'POLLO', '2026-04-09 15:26:43', '2026-04-09 15:26:43'),
(3, 'ITEM-9B0BB', 'arroz', '2026-04-09 22:31:18', '2026-04-09 22:31:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_venta` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `stock_minimo`, `activo`, `created_at`, `updated_at`, `imagen`) VALUES
(1, 'Pan Blanco', 'Pan blanco artesanal por unidad', 0.50, 1.00, 99, 20, 1, '2026-03-30 21:03:25', '2026-04-06 22:24:59', NULL),
(2, 'Pan Integral', 'Pan integral artesanal por unidad', 0.70, 1.50, 46, 10, 1, '2026-03-30 21:03:25', '2026-04-06 15:31:14', NULL),
(3, 'Pastel de Chocolate', 'Pastel pequeño de chocolate', 8.00, 15.00, 0, 3, 1, '2026-03-30 21:03:25', '2026-04-09 21:18:41', NULL),
(4, 'Croissant', 'Croissant de mantequilla', 1.20, 2.50, 20, 5, 1, '2026-03-30 21:03:25', '2026-04-03 22:14:18', NULL),
(5, 'Galletas', 'Paquete de galletas artesanales', 2.00, 4.00, 36, 8, 1, '2026-03-30 21:03:25', '2026-04-06 22:15:13', NULL),
(6, 'Edwin Andres Solis Borja', 'es muy rico', 7.00, 8.00, 8, 7, 0, '2026-04-06 15:35:10', '2026-04-06 15:35:35', '1775489710_Screenshot_2025_11_25_103238.png'),
(7, 'Luz Maritza Maldonado Rojas', 'hola', 8000.00, 9000.00, 10, 5, 0, '2026-04-06 22:17:08', '2026-04-06 22:17:28', '1775513828_Screenshot_2025_11_25_103238.png'),
(8, 'pizza', 'muy rica', 3000.00, 7000.00, 18, 5, 0, '2026-04-09 13:21:44', '2026-04-09 13:28:44', '1775740904_Screenshot_2025_11_25_103238.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `rol`, `activo`, `created_at`) VALUES
(1, 'Administrador', 'admin@catnisbakery.com', '$2y$10$FycCnvNAbb9qw1.y9vyoO.ZakmRyLz0hHDDS0fWJLLHU.a625pRB2', 'admin', 1, '2026-03-30 21:03:25'),
(2, 'Andres', 'soporte@daservice.com.co', '$2y$10$mCP8pq7FDUoI9qqqqPMVsuvxfnjxo7szy/swLRgkpLaBsF1AeDUm2', 'usuario', 1, '2026-04-06 13:15:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `tipo` enum('contado','credito') DEFAULT 'contado',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `metodo_pago` enum('efectivo','transferencia','otros') DEFAULT 'efectivo',
  `estado` enum('completada','pendiente','cancelada') DEFAULT 'completada',
  `notas` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `usuario_id`, `cliente_id`, `tipo`, `total`, `metodo_pago`, `estado`, `notas`, `fecha`) VALUES
(5, 1, 3, 'credito', 12.50, 'efectivo', 'completada', '', '2026-04-03 22:13:24'),
(6, 1, 2, 'contado', 12.50, 'efectivo', 'completada', '', '2026-04-03 22:14:18'),
(7, 2, 2, 'credito', 6.00, 'efectivo', 'completada', '', '2026-04-06 15:31:14'),
(8, 1, NULL, 'contado', 16.00, 'transferencia', 'completada', '', '2026-04-06 21:15:13'),
(9, 1, NULL, 'contado', 1.00, 'efectivo', 'completada', '', '2026-04-06 21:24:59'),
(10, 1, 3, 'credito', 15.00, 'transferencia', 'completada', '', '2026-04-09 12:11:20'),
(11, 1, 2, 'credito', 135.00, 'transferencia', 'completada', '', '2026-04-09 20:18:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `abonos`
--
ALTER TABLE `abonos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deuda_id` (`deuda_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_gastos`
--
ALTER TABLE `detalle_gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gasto_id` (`gasto_id`),
  ADD KEY `item_maestro_id` (`item_maestro_id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `deudas`
--
ALTER TABLE `deudas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `items_maestro_gastos`
--
ALTER TABLE `items_maestro_gastos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `abonos`
--
ALTER TABLE `abonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_gastos`
--
ALTER TABLE `detalle_gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `deudas`
--
ALTER TABLE `deudas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `items_maestro_gastos`
--
ALTER TABLE `items_maestro_gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `abonos`
--
ALTER TABLE `abonos`
  ADD CONSTRAINT `abonos_ibfk_1` FOREIGN KEY (`deuda_id`) REFERENCES `deudas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_gastos`
--
ALTER TABLE `detalle_gastos`
  ADD CONSTRAINT `detalle_gastos_ibfk_1` FOREIGN KEY (`gasto_id`) REFERENCES `gastos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_gastos_ibfk_2` FOREIGN KEY (`item_maestro_id`) REFERENCES `items_maestro_gastos` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `deudas`
--
ALTER TABLE `deudas`
  ADD CONSTRAINT `deudas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deudas_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
