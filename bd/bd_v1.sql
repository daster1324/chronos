-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2018 a las 23:16:09
-- Versión del servidor: 10.1.32-MariaDB
-- Versión de PHP: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chronos`
--
CREATE DATABASE IF NOT EXISTS `chronos` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE `chronos`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(10) NOT NULL,
  `id_carrera` int(2) NOT NULL,
  `itinerario` varchar(5) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `abreviatura` varchar(10) COLLATE latin1_spanish_ci DEFAULT NULL,
  `curso` varchar(1) COLLATE latin1_spanish_ci NOT NULL,
  `id_departamento` int(2) NOT NULL,
  `id_departamento_dos` int(2) DEFAULT NULL,
  `creditos` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Truncar tablas antes de insertar `asignaturas`
--

TRUNCATE TABLE `asignaturas`;
--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id`, `id_carrera`, `itinerario`, `nombre`, `abreviatura`, `curso`, `id_departamento`, `id_departamento_dos`, `creditos`) VALUES
(1, 1, 'ii', 'TEST', 'TST', '1', 1, NULL, 1),
(2, 2, 'ii', 'test', 'tst', '1', 1, NULL, 12),
(3, 2, 'ii', 'test', 'tst', '1', 1, NULL, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id` int(2) NOT NULL,
  `nombre` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `id_facultad` int(2) NOT NULL,
  `id_facultad_dg` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Truncar tablas antes de insertar `carreras`
--

TRUNCATE TABLE `carreras`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id` bigint(15) NOT NULL,
  `id_asignatura` int(10) NOT NULL,
  `cuatrimestre` int(1) NOT NULL,
  `dia` varchar(1) COLLATE latin1_spanish_ci NOT NULL,
  `hora` int(2) NOT NULL,
  `grupo` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `edificio` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Truncar tablas antes de insertar `clases`
--

TRUNCATE TABLE `clases`;
--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id`, `id_asignatura`, `cuatrimestre`, `dia`, `hora`, `grupo`, `edificio`) VALUES
(1, 1, 1, 'l', 1, 'a', 1),
(2, 2, 1, 'm', 2, 'a', 1),
(3, 2, 2, 'm', 2, 'a', 1),
(4, 2, 2, 'm', 2, 'a', 1),
(5, 2, 2, 'm', 2, 'a', 1),
(6, 2, 2, 'm', 2, 'a', 1),
(7, 2, 2, 'm', 2, 'a', 1),
(8, 2, 2, 'm', 2, 'a', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(2) NOT NULL,
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `id_facultad` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Truncar tablas antes de insertar `departamentos`
--

TRUNCATE TABLE `departamentos`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facultades`
--

CREATE TABLE `facultades` (
  `id` int(2) NOT NULL,
  `nombre` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `campus` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Truncar tablas antes de insertar `facultades`
--

TRUNCATE TABLE `facultades`;
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_carrera` (`id_carrera`),
  ADD KEY `id_departamento` (`id_departamento`),
  ADD KEY `itinerario` (`itinerario`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_facultad` (`id_facultad`),
  ADD KEY `id_facultad_dg` (`id_facultad_dg`);

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_asignatura` (`id_asignatura`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_facultad` (`id_facultad`);

--
-- Indices de la tabla `facultades`
--
ALTER TABLE `facultades`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facultades`
--
ALTER TABLE `facultades`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
