-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-02-2025 a las 23:30:46
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
-- Base de datos: `cervecerialaura`
--
CREATE DATABASE IF NOT EXISTS `cervecerialaura` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cervecerialaura`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `ID_CARRITO` int(3) NOT NULL,
  `Id_usuario` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`ID_CARRITO`, `Id_usuario`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID_PRODUCTO` int(11) NOT NULL,
  `Denominacion_Cerveza` varchar(100) NOT NULL,
  `Marca` varchar(50) NOT NULL,
  `Tipo_Cerveza` varchar(100) NOT NULL,
  `Formato` varchar(100) NOT NULL,
  `Cantidad` varchar(25) NOT NULL,
  `Alergias` varchar(40) DEFAULT NULL,
  `Fecha_Consumo` date NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Precio` float NOT NULL,
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID_PRODUCTO`, `Denominacion_Cerveza`, `Marca`, `Tipo_Cerveza`, `Formato`, `Cantidad`, `Alergias`, `Fecha_Consumo`, `Foto`, `Precio`, `Observaciones`) VALUES
(1, 'Alhambra Roja', 'Alhambra', 'Lager', 'Botella', 'Tercio', NULL, '2025-02-22', 'archivos/Alhambra_Roja-600x800.jpg', 2.5, 'Está deliciosa'),
(2, 'Cruzcampo Especial', 'Cruzcampo', 'Rubia', 'Botella', 'Tercio', 'gluten', '2025-05-15', 'archivos/cruzcampoespecial.jpg', 1.2, 'Es especialita'),
(3, 'MAHOU CLASICA', 'Mahou', 'Rubia', 'Pack', 'Botellin', 'sinAlergenos', '2025-08-27', 'archivos/mahou.jpg', 16.35, 'cerveza rubia original pack 28 latas 33 cl'),
(4, 'MAHOU CLASICA LATA', 'Mahou', 'Rubia', 'Lata', 'Botellin', 'sinAlergenos', '2025-09-18', 'archivos/mahou_lata.jpg', 0.76, 'Pequeña pero barata'),
(5, 'Cerveza rubia especial Mini', 'Estrella Galicia', 'Rubia', 'Pack', 'Tercio', 'gluten', '2025-05-30', 'archivos/estrella.jpg', 7.12, 'cerveza rubia especial Mini pack 12 botellas 20 cl'),
(6, 'Cerveza negra', 'Artesana', 'Cerveza Negra', 'Botella', 'Litro', 'sulfitos', '2025-02-28', '', 5.5, 'Alta calidad'),
(7, 'LEFFE', 'Artesana', 'Rubia', 'Botella', 'Medio litro', 'huevo', '2025-03-07', 'archivos/LEFFE.jpg', 2.49, 'cerveza triple belga de abadía botella'),
(8, 'ESTRELLA DAMM', 'Damm', 'Rubia', 'Lata', 'Medio litro', 'huevo', '2025-04-24', 'archivos/DAMM.jpg', 1.39, 'cerveza rubia mediterránea de malta, arroz y lúpulo lata 50 cl'),
(9, 'HEINEKEN SILVER', 'Heineken', 'Lager', 'Lata', 'Medio litro', 'lacteo', '2025-05-16', 'archivos/HEONEKEN.jpg', 0.9, 'cerveza rubia Lager holandesa lata 33 cl'),
(10, 'SIERRA NEVADA', 'Artesana', 'Pale Ale', 'Lata', 'Botellin', 'gluten, sulfitos', '2025-02-28', 'archivos/SIERRA_NEVADA.jpg', 3.26, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_carritos`
--

CREATE TABLE `productos_carritos` (
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_USUARIO` int(3) NOT NULL,
  `CORREO` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `EDAD` int(3) NOT NULL,
  `PERFIL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_USUARIO`, `CORREO`, `PASSWORD`, `EDAD`, `PERFIL`) VALUES
(2, 'root', '$2y$10$z/tpWPXy0DbTedXfHjwlteM90Gdej3dlceSIJHmAw0UiOYkkMieYO', 0, ''),
(3, 'user@gmail.com', '$2y$10$MnIpEMNagp8ia.Tn5v0CLubDo2WeZHPGRN9.uuPc9h8TIIwuJh0aa', 0, '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`ID_CARRITO`),
  ADD UNIQUE KEY `Id_usuario_2` (`Id_usuario`),
  ADD KEY `Id_usuario` (`Id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID_PRODUCTO`);

--
-- Indices de la tabla `productos_carritos`
--
ALTER TABLE `productos_carritos`
  ADD PRIMARY KEY (`id_producto`,`id_carrito`),
  ADD KEY `id_carrito` (`id_carrito`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_USUARIO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `ID_CARRITO` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID_PRODUCTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_USUARIO` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuario` (`ID_USUARIO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productos_carritos`
--
ALTER TABLE `productos_carritos`
  ADD CONSTRAINT `productos_carritos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`ID_PRODUCTO`) ON DELETE CASCADE,
  ADD CONSTRAINT `productos_carritos_ibfk_2` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`ID_CARRITO`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
