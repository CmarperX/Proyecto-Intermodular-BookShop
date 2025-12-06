-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2025 a las 14:14:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bookshop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`) VALUES
(1, 'Realismo mágico'),
(2, 'Distopía'),
(3, 'Fábula'),
(4, 'Novela'),
(5, 'Ciencia ficción'),
(6, 'Misterio'),
(7, 'Romántica'),
(8, 'Aventura'),
(9, 'Fantasía'),
(10, 'Histórica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_compra` date DEFAULT curdate(),
  `estado_compra` enum('pendiente','pagada','enviada','entregada','cancelada') DEFAULT 'pendiente',
  `precio_total` decimal(9,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id_compra`, `id_usuario`, `fecha_compra`, `estado_compra`, `precio_total`) VALUES
(1, 1, '2025-07-11', 'pagada', 19.99),
(2, 2, '2025-07-12', 'enviada', 14.50),
(3, 3, '2025-07-13', 'entregada', 24.90),
(4, 4, '2025-07-14', 'pendiente', 25.50),
(5, 5, '2025-07-15', 'pagada', 15.80),
(6, 6, '2025-07-16', 'pagada', 21.50),
(7, 7, '2025-07-17', 'cancelada', 13.40),
(8, 8, '2025-07-18', 'entregada', 11.25),
(9, 9, '2025-07-19', 'enviada', 18.90),
(10, 10, '2025-07-20', 'pendiente', 17.60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `precio_unitario` decimal(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id_detalle_compra`, `id_compra`, `id_libro`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 1, 19.99),
(2, 2, 2, 1, 14.50),
(3, 3, 4, 1, 24.90),
(4, 4, 3, 2, 12.75),
(5, 5, 5, 1, 15.80),
(6, 6, 6, 1, 21.50),
(7, 7, 7, 2, 13.40),
(8, 8, 8, 1, 11.25),
(9, 9, 9, 1, 18.90),
(10, 10, 10, 1, 17.60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_reserva`
--

CREATE TABLE `detalle_reserva` (
  `id_detalle_reserva` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `precio_unitario` decimal(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_reserva`
--

INSERT INTO `detalle_reserva` (`id_detalle_reserva`, `id_reserva`, `id_libro`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 1, 19.99),
(2, 2, 2, 1, 14.50),
(3, 3, 4, 1, 24.90),
(4, 4, 3, 2, 12.75),
(5, 5, 5, 1, 15.80),
(6, 6, 6, 1, 21.50),
(7, 7, 7, 2, 13.40),
(8, 8, 8, 1, 11.25),
(9, 9, 9, 1, 18.90),
(10, 10, 10, 1, 17.60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id_libro` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `isbn` varchar(30) NOT NULL,
  `autor` varchar(50) DEFAULT NULL,
  `editorial` varchar(50) DEFAULT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `precio` decimal(7,2) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`id_libro`, `titulo`, `isbn`, `autor`, `editorial`, `fecha_publicacion`, `stock`, `precio`, `id_categoria`) VALUES
(1, 'Cien años de soledad', '9780307474728', 'Gabriel García Márquez', 'Sudamericana', '1967-05-30', 5, 19.99, 1),
(2, '1984', '9780451524935', 'George Orwell', 'Secker & Warburg', '1949-06-08', 8, 14.50, 2),
(3, 'El principito', '9780156012195', 'Antoine de Saint-Exupéry', 'Reynal & Hitchcock', '1943-04-06', 6, 12.75, 3),
(4, 'Don Quijote de la Mancha', '9788420412141', 'Miguel de Cervantes', 'Francisco de Robles', '1605-01-16', 3, 24.90, 4),
(5, 'Fahrenheit 451', '9781451673319', 'Ray Bradbury', 'Ballantine Books', '1953-10-19', 7, 15.80, 5),
(6, 'La sombra del viento', '9788408172173', 'Carlos Ruiz Zafón', 'Planeta', '2001-06-06', 9, 21.50, 6),
(7, 'Orgullo y prejuicio', '9780141439518', 'Jane Austen', 'T. Egerton', '1813-01-28', 10, 13.40, 7),
(8, 'Crónica de una muerte anunciada', '9780307387707', 'Gabriel García Márquez', 'La Oveja Negra', '1981-03-01', 4, 11.25, 4),
(9, 'El Hobbit', '9780345339683', 'J.R.R. Tolkien', 'George Allen & Unwin', '1937-09-21', 6, 18.90, 9),
(10, 'Los juegos del hambre', '9780439023528', 'Suzanne Collins', 'Scholastic Press', '2008-09-14', 12, 17.60, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `aviso` text NOT NULL,
  `estado_notificacion` enum('pendiente','enviada','leida') DEFAULT 'pendiente',
  `fecha_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificacion`
--

INSERT INTO `notificacion` (`id_notificacion`, `id_usuario`, `aviso`, `estado_notificacion`, `fecha_envio`) VALUES
(1, 1, 'Tu reserva ha sido procesada.', 'enviada', '2025-07-01 00:00:00'),
(2, 2, 'Tu compra está en camino.', 'enviada', '2025-07-02 00:00:00'),
(3, 3, 'Has cancelado una reserva.', 'leida', '2025-07-03 00:00:00'),
(4, 4, 'Libro reservado disponible.', 'pendiente', '2025-07-04 00:00:00'),
(5, 5, 'Pago recibido correctamente.', 'leida', '2025-07-05 00:00:00'),
(6, 6, 'Tu pedido ha sido entregado.', 'leida', '2025-07-06 00:00:00'),
(7, 7, 'Tu reserva fue confirmada.', 'enviada', '2025-07-07 00:00:00'),
(8, 8, 'Tu compra fue cancelada.', 'pendiente', '2025-07-08 00:00:00'),
(9, 9, 'Actualización de estado de compra.', 'enviada', '2025-07-09 00:00:00'),
(10, 10, 'Gracias por tu compra.', 'leida', '2025-07-10 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `metodo_pago` enum('tarjeta','paypal','transferencia','efectivo') NOT NULL,
  `cantidad_pagada` decimal(7,2) NOT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `estado_pago` enum('pendiente','procesando','completado','fallido','reembolsado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `id_compra`, `id_reserva`, `metodo_pago`, `cantidad_pagada`, `fecha_pago`, `estado_pago`) VALUES
(1, 1, NULL, 'tarjeta', 19.99, '2025-07-11 00:00:00', 'completado'),
(2, 2, NULL, 'paypal', 14.50, '2025-07-12 00:00:00', 'completado'),
(3, 3, NULL, 'transferencia', 24.90, '2025-07-13 00:00:00', 'completado'),
(4, 4, NULL, 'tarjeta', 25.50, '2025-07-14 00:00:00', 'pendiente'),
(5, 5, NULL, 'efectivo', 15.80, '2025-07-15 00:00:00', 'completado'),
(6, 6, NULL, 'tarjeta', 21.50, '2025-07-16 00:00:00', 'completado'),
(7, 7, NULL, 'paypal', 13.40, '2025-07-17 00:00:00', 'fallido'),
(8, 8, NULL, 'efectivo', 11.25, '2025-07-18 00:00:00', 'completado'),
(9, 9, NULL, 'tarjeta', 18.90, '2025-07-19 00:00:00', 'procesando'),
(10, 10, NULL, 'transferencia', 17.60, '2025-07-20 00:00:00', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `direccion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'Editorial Sudamericana', '111111111', 'sudamericana@libros.com', 'Buenos Aires 101'),
(2, 'Planeta Libros', '222222222', 'planeta@libros.com', 'Barcelona 55'),
(3, 'Penguin Random House', '333333333', 'penguin@libros.com', 'Madrid 90'),
(4, 'Secker & Warburg', '444444444', 'secker@libros.com', 'Londres 77'),
(5, 'Scholastic Press', '555555555', 'scholastic@libros.com', 'New York 21'),
(6, 'Ballantine Books', '666666666', 'ballantine@libros.com', 'Los Ángeles 12'),
(7, 'La Oveja Negra', '777777777', 'ovejanegra@libros.com', 'Bogotá 44'),
(8, 'T. Egerton', '888888888', 'egerton@libros.com', 'Londres 30'),
(9, 'George Allen & Unwin', '999999999', 'allen@libros.com', 'Oxford 25'),
(10, 'Reynal & Hitchcock', '101010101', 'reynal@libros.com', 'París 19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_reserva` date DEFAULT curdate(),
  `estado_reserva` enum('pendiente','confirmada','cancelada','completada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `id_usuario`, `fecha_reserva`, `estado_reserva`) VALUES
(1, 1, '2025-06-01', 'pendiente'),
(2, 2, '2025-06-03', 'completada'),
(3, 3, '2025-06-05', 'cancelada'),
(4, 4, '2025-06-10', 'pendiente'),
(5, 5, '2025-06-15', 'pendiente'),
(6, 6, '2025-06-20', 'completada'),
(7, 7, '2025-06-25', 'pendiente'),
(8, 8, '2025-07-01', 'pendiente'),
(9, 9, '2025-07-05', 'cancelada'),
(10, 10, '2025-07-10', 'completada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suministro`
--

CREATE TABLE `suministro` (
  `id_suministro` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 0,
  `fecha_suministro` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suministro`
--

INSERT INTO `suministro` (`id_suministro`, `id_libro`, `id_proveedor`, `precio_compra`, `cantidad`, `fecha_suministro`) VALUES
(1, 1, 1, 12.50, 10, '2025-03-01'),
(2, 2, 4, 9.00, 15, '2025-03-05'),
(3, 3, 10, 8.20, 20, '2025-03-07'),
(4, 4, 1, 15.00, 5, '2025-03-10'),
(5, 5, 6, 10.50, 10, '2025-03-15'),
(6, 6, 2, 14.75, 8, '2025-03-20'),
(7, 7, 8, 9.90, 12, '2025-03-25'),
(8, 8, 7, 7.80, 7, '2025-03-28'),
(9, 9, 9, 12.10, 6, '2025-04-01'),
(10, 10, 5, 11.40, 10, '2025-04-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contraseña` varchar(30) NOT NULL,
  `tipo_usuario` enum('cliente','admin') DEFAULT 'cliente',
  `estado_usuario` enum('activo','inactivo','bloqueado') DEFAULT 'activo',
  `fecha_registro` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `direccion`, `email`, `contraseña`, `tipo_usuario`, `estado_usuario`, `fecha_registro`) VALUES
(1, 'Ana', 'López', 'Av. San Martín 123', 'ana@gmail.com', '1234', 'cliente', 'activo', '2025-01-15'),
(2, 'Carlos', 'Martínez', 'Calle Rivadavia 450', 'carlos@gmail.com', 'abcd', 'cliente', 'activo', '2025-02-03'),
(3, 'Lucía', 'Torres', 'Pasaje Mitre 220', 'lucia@admin.com', 'admin1', 'admin', 'activo', '2025-03-10'),
(4, 'Pablo', 'Suárez', 'Calle Moreno 777', 'pablo@editor.com', 'edit1', 'cliente', 'activo', '2025-03-20'),
(5, 'María', 'Fernández', 'Av. Belgrano 505', 'maria@gmail.com', '5678', 'cliente', 'activo', '2025-04-01'),
(6, 'Javier', 'Gómez', 'Calle Corrientes 88', 'javier@gmail.com', 'pass123', 'cliente', 'activo', '2025-04-15'),
(7, 'Rocío', 'Vega', 'Bv. Sarmiento 302', 'rocio@editor.com', 'edit2', 'cliente', 'activo', '2025-05-05'),
(8, 'Martina', 'Silva', 'Av. Colón 932', 'martina@gmail.com', 'pass456', 'cliente', 'activo', '2025-05-22'),
(9, 'Fernando', 'Navarro', 'Calle San Luis 670', 'fernando@admin.com', 'admin2', 'admin', 'activo', '2025-06-10'),
(10, 'Sofía', 'Ramos', 'Calle Catamarca 55', 'sofia@gmail.com', 'sofia12', 'cliente', 'activo', '2025-06-25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id_detalle_compra`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `detalle_reserva`
--
ALTER TABLE `detalle_reserva`
  ADD PRIMARY KEY (`id_detalle_reserva`),
  ADD KEY `id_reserva` (`id_reserva`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id_libro`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `suministro`
--
ALTER TABLE `suministro`
  ADD PRIMARY KEY (`id_suministro`),
  ADD KEY `id_libro` (`id_libro`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `detalle_reserva`
--
ALTER TABLE `detalle_reserva`
  MODIFY `id_detalle_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `suministro`
--
ALTER TABLE `suministro`
  MODIFY `id_suministro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_reserva`
--
ALTER TABLE `detalle_reserva`
  ADD CONSTRAINT `detalle_reserva_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_reserva_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`id_reserva`) REFERENCES `reserva` (`id_reserva`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `suministro`
--
ALTER TABLE `suministro`
  ADD CONSTRAINT `suministro_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `suministro_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
