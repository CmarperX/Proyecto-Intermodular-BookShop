-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-01-2026 a las 22:53:36
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
-- Base de datos: `savia_nexus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `activo` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `codigo` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `isbn` varchar(30) NOT NULL,
  `autor` varchar(50) DEFAULT NULL,
  `editorial` varchar(50) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 1,
  `codCategoria` int(11) DEFAULT NULL,
  `activo` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`codigo`, `titulo`, `isbn`, `autor`, `editorial`, `descripcion`, `imagen`, `stock`, `codCategoria`, `activo`) VALUES
(4, 'One Piece nº 12', '9788410492653', 'Eiichiro Oda', 'Planeta comic', 'Luffy, gracias a la fuerza adicional que le otorga el peinado afro, logra imponerse en la lucha contra Davy frente a Zorro Plateado. Es el momento de dirigirse a Water Seven, la &quot;Ciudad del agua&quot;, lugar en el que esperan encontrar un buen c', 'book_696e9b985b6494.21673212.jpg', 10, NULL, 'activo'),
(5, 'Realidades a medida', '9788410466203', 'Brandon Sanderson', 'Nova', 'Las historias de esta antología, que abarca los géneros de la fantasía y la ciencia ficción, están ambientadas más allá de los confines del Cosmere.', 'book_696e9c5c77e5a7.99519465.jpg', 10, NULL, 'activo'),
(6, 'El Hobbit', '9788445000665', 'J.R.R. Tolkien', 'Minotauro', 'Bilbo Bolsón es como cualquier hobbit: no mide más de metro y medio, vive pacíficamente en la Comarca, y su máxima aspiración es disfrutar de los placeres sencillos de la vida (comer bien, pasear y charlar con los amigos). Pero su tranquilidad se ve ', 'book_696e9c96a4b729.72503891.jpg', 10, NULL, 'activo'),
(7, 'Asesinato en el Orient Express', '9788467045413', 'Agatha Christie', 'Espasa', 'En un lugar aislado de la antigua Yugoslavia, en plena madrugada, una fuerte tormenta de nieve obstaculiza la línea férrea por donde circula el Orient Express. Procedente de la exótica Estambul, en él viaja el detective Hércules Poirot, que repentina', 'book_696e9cefcb0f29.03583478.jpg', 10, NULL, 'activo'),
(8, 'El príncipe de la niebla', '9788408163541', 'Carlos Ruiz Zafón', 'Planeta', 'El nuevo hogar de los Carver, que se han mudado a la costa huyendo de la ciudad y de la guerra, está rodeado de misterio.Todavía se respira el espíritu de Jacob, el hijo de los antiguos propietarios, que murió ahogado. Las extrañas circunstancias de ', 'book_696ea046e611e8.59310721.jpg', 10, NULL, 'activo'),
(9, 'El alquimista', '9786073809603', 'Paulo Coelho', 'Grijalbo', 'Considerado ya un clásico de nuestros días, El Alquimista relata las aventuras de Santiago, un joven pastor andaluz que un día emprende un viaje por las arenas del desierto en busca de un tesoro. Lo que empieza como la búsqueda de bienes mundanos se ', 'book_696ea07f3f3194.93019286.jpg', 10, NULL, 'activo'),
(10, 'El amor del Highlander', '9789083218175', 'Mariah Stone', 'Stone Publishing', 'Luego de que la incriminaran en el asesinato de su exnovio, la oficial del ejército estadounidense Amber Ryan huye a Escocia y se esconde en las ruinas del castillo de Inverlochy. Allí, un hada de las Tierras Altas la envía al año 1308: directo al me', 'book_696ea29dafccd8.79916631.jpg', 10, NULL, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `codigo` int(11) NOT NULL,
  `codUsuario` varchar(9) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `leida` enum('leida','no leida') DEFAULT 'no leida'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `codigo` int(11) NOT NULL,
  `codUsuario` varchar(9) DEFAULT NULL,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `recurso` enum('libro','sala','ordenador') NOT NULL,
  `codRecurso` int(11) NOT NULL,
  `estado` enum('confirmada','cancelada','finalizada') DEFAULT 'confirmada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `clave` varchar(250) NOT NULL,
  `rol` enum('cliente','empleado','admin') DEFAULT 'cliente',
  `activo` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`dni`, `nombre`, `apellido`, `direccion`, `email`, `telefono`, `clave`, `rol`, `activo`) VALUES
('74390499N', 'Cristian', 'Marín', 'Calle Mariano Benlliure', 'c@gmail.com', '697284790', '$2y$10$DUvotQ0jhBnnl/j8FIlI1uK88f9ugSsoWkjTi7jCbaeJ5.GJu0UaK', 'cliente', 'activo'),
('76537668N', 'Profesor', 'Severo', 'instituto severo', 'profesor@severo.com', '669334554', '$2y$10$was53Hb5YIv9z/8maEy0E.siVeZzD36Rv.xfls7DVljalq9WsyHSi', 'admin', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_libros_categorias` (`codCategoria`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `notificaciones_ibfk_1` (`codUsuario`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_reserva_sala` (`codRecurso`),
  ADD KEY `fk_reserva_usuario` (`codUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `fk_libros_categorias` FOREIGN KEY (`codCategoria`) REFERENCES `categorias` (`codigo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`codUsuario`) REFERENCES `usuarios` (`dni`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`codUsuario`) REFERENCES `usuarios` (`dni`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
