-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-11-2025 a las 23:46:53
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
-- Base de datos: `fidelizacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficios`
--

CREATE TABLE `beneficios` (
  `id` int(11) NOT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `beneficios`
--

INSERT INTO `beneficios` (`id`, `empresa`, `descripcion`, `logo`) VALUES
(1, 'Cristal', 'Beneficios de refrescos de 600ml.', 'cristal.webp'),
(2, 'Pepsi Cola', 'Beneficios de refrescos embasados', 'pepsi.svg'),
(3, 'Restaurante Rustika ', 'Ofrece todas las comidas ricas, alitas, hamburguesas, papas, etc…', 'IMG_2084.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canjes`
--

CREATE TABLE `canjes` (
  `id` int(11) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `premio_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `canjes`
--

INSERT INTO `canjes` (`id`, `telefono`, `premio_id`, `fecha`) VALUES
(1, '9992000107', 1, '2025-06-20 07:39:22'),
(2, '9992000107', 1, '2025-06-20 08:08:00'),
(3, '9992000107', 7, '2025-06-20 08:08:04'),
(4, '9992000107', 7, '2025-06-20 08:08:07'),
(5, '9992000107', 7, '2025-06-20 08:08:10'),
(6, '9992000107', 1, '2025-06-20 08:15:29'),
(7, '9992000107', 7, '2025-06-20 08:15:35'),
(8, '9992000107', 1, '2025-07-03 21:47:46'),
(9, '1234567890', 7, '2025-07-03 22:08:51'),
(10, '9992000107', 1, '2025-09-24 08:04:13'),
(11, '9992000107', 7, '2025-10-07 19:29:00'),
(12, '9992000107', 7, '2025-10-07 19:29:20'),
(13, '9992000107', 7, '2025-10-07 19:29:26'),
(14, '9992000107', 7, '2025-10-07 19:29:33'),
(15, '9992000107', 1, '2025-10-07 19:30:43'),
(16, '9992000107', 1, '2025-10-07 19:41:42'),
(17, '9992000107', 1, '2025-10-07 19:41:53'),
(18, '9992000107', 1, '2025-10-07 19:42:36'),
(19, '9992000107', 7, '2025-10-07 19:44:56'),
(20, '9992000107', 7, '2025-10-07 19:45:06'),
(21, '9992000107', 1, '2025-10-07 19:53:00'),
(22, '9992000107', 1, '2025-10-07 19:53:18'),
(23, '9992000107', 1, '2025-10-07 19:53:25'),
(24, '9992000107', 1, '2025-10-07 19:53:28'),
(25, '9992000107', 1, '2025-10-07 19:53:31'),
(26, '9992000107', 1, '2025-10-07 19:54:12'),
(27, '9992000107', 1, '2025-10-07 19:54:42'),
(28, '9992000107', 1, '2025-10-07 19:56:32'),
(29, '9992000107', 1, '2025-10-07 19:57:41'),
(30, '9992000107', 1, '2025-10-07 19:57:44'),
(31, '9992000107', 1, '2025-10-07 19:59:42'),
(32, '9992000107', 1, '2025-10-21 13:01:51'),
(33, '9992000107', 1, '2025-10-24 23:42:50'),
(34, '9992000107', 1, '2025-10-24 23:42:54'),
(35, '9992000107', 1, '2025-10-24 23:42:59'),
(36, '9992000107', 1, '2025-10-24 23:58:07'),
(37, '9992000107', 1, '2025-10-25 01:45:29'),
(38, '9992000107', 1, '2025-10-25 01:45:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `telefono` varchar(15) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(50) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `puntos` int(11) DEFAULT 0,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`telefono`, `nombre`, `apellidos`, `direccion`, `correo`, `foto`, `estado`, `ciudad`, `puntos`, `password`) VALUES
('1234567890', 'Carlos ', 'Chuc', 'Calle desconocida', 'carlos@gmail.com', NULL, 'Yucatán', 'Tekax', 5, '$2y$10$stUUNIjgc5ZaGUVvv1mF4umxU7nhcY3MddXrXY5D6KsrnHgUtw7yW'),
('9961109956', 'Albert Chan ', 'Chan Zib', 'Sn', 'ichan.zib@gamil.com', '1763764552_IMG_1923.jpeg', 'Yucatán ', 'Tekax', 5500, '$2y$10$V8cKwzNCE.7PJT1S862EJeyFEZBBrzcTsCUp56MrR4vFLZPJ8zUMK'),
('9992000107', 'Manuel puc', 'Ek', 'desconocido', 'manuel@gmail.com', '1763764822_IMG_1996.jpeg', 'Yucatán', 'Tekax', 4495, '$2y$10$dPZHSqafM56TCqy2mEqNBuAIPRpX8P/ZKJvtp3om.SiwyKKb1lrP2'),
('admin', 'Admin', 'admin', 'calle 43 x 66 y 68', 'ichan.zib@gmail.com', 'uploads/admin.jpg', 'Yucatán', 'Tekax', 0, '$2y$10$LF6GUL4/B5thplk2PU99LOtfl3ilcqVRcFBQO8gv6tg9MKXZbuxvO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `premios`
--

CREATE TABLE `premios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `puntos_requeridos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `premios`
--

INSERT INTO `premios` (`id`, `nombre`, `descripcion`, `imagen`, `puntos_requeridos`) VALUES
(1, 'Coca Cola', 'Refresco de coca cola de 600ml', 'coca.webp', 20),
(7, 'Laptop Gamer Lenovo Legion 5', 'Memoria RAM de 32 GM\r\nCapacidad de memoria de 1T\r\nProcesador Inter Core I9 15G', 'LEGION.jpg', 5000),
(8, 'Cera - pomada para el cabello ', 'Deja fijo el cabello y huele rico', 'IMG_2188.jpeg', 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas`
--

CREATE TABLE `tarjetas` (
  `id` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `banco` varchar(50) NOT NULL,
  `fecha_vencimiento` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarjetas`
--

INSERT INTO `tarjetas` (`id`, `telefono`, `numero`, `banco`, `fecha_vencimiento`, `created_at`) VALUES
(1, '9992000107', '1234567891234567', 'Banco Azteca', '2025-10', '2025-10-23 15:51:16'),
(2, '9961109956', '1234567890123456', 'BBAV', '2025-01', '2025-11-21 20:28:31'),
(3, '9961109956', '1234567890123456', 'SANTANTER', '2025-11', '2025-11-21 20:29:13');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `beneficios`
--
ALTER TABLE `beneficios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `canjes`
--
ALTER TABLE `canjes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `telefono` (`telefono`),
  ADD KEY `premio_id` (`premio_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`telefono`);

--
-- Indices de la tabla `premios`
--
ALTER TABLE `premios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `telefono` (`telefono`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `beneficios`
--
ALTER TABLE `beneficios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `canjes`
--
ALTER TABLE `canjes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `premios`
--
ALTER TABLE `premios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `canjes`
--
ALTER TABLE `canjes`
  ADD CONSTRAINT `canjes_ibfk_1` FOREIGN KEY (`telefono`) REFERENCES `clientes` (`telefono`),
  ADD CONSTRAINT `canjes_ibfk_2` FOREIGN KEY (`premio_id`) REFERENCES `premios` (`id`);

--
-- Filtros para la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD CONSTRAINT `tarjetas_ibfk_1` FOREIGN KEY (`telefono`) REFERENCES `clientes` (`telefono`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
